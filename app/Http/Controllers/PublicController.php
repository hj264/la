<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\CustomerController;
use App\Models\ChatModel;
use App\Models\CustomerModel;
use App\Service\ChatService;
use App\Service\HelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
use App\Models\FryModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class PublicController extends Controller
{
    // 对话注册
    public function registerChat(Request $request): JsonResponse
    {
        $lan = str_replace('-', '_', trim($request->input('language')));


        // 获取随机客服
        $langInfo = ChatModel::where([
            'type' => 1,
            'language' => $lan
        ])->first();
        if (!$langInfo) {
            $langInfo = ChatModel::where([
                'type' => 1,
                'language' => 'hk'
            ])->first();
        }

        if (!$langInfo) {
            return $this->error_("没有对应的客服信息");
        }
        // 根据模板获取一个随机客服
        $customerList = CustomerModel::where('chat_id', $langInfo->id)->get();
        $customerInfo = $customerList[mt_rand(0, count($customerList) - 1)];
        $customerInfo = array_merge($langInfo->toArray(), $customerInfo->toArray());
        $customerInfo['FirstImg'] = env('APP_URL') . '/storage/' . $customerInfo['first_img'];
        $customerInfo['SecondImg'] = env('APP_URL') . '/storage/' . $customerInfo['second_img'];
        $customerInfo['SecondIosImg'] = env('APP_URL') . '/storage/' . $customerInfo['second_ios_img'];


        $res = ChatService::getInstance()->registerOrLogin(
            null,
            $userInfo->id ?? 0,
            1,
            1,
        );
        $res['customerInfo'] = $customerInfo;
        return $res['status'] ? $this->success($res) : $this->error_($res['msg']);
    }

    // 获取socket 地址
    function getSocketAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|max:255',
            'language' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $type = $request->input('type');
        $lan = str_replace('-', '_', trim($request->input('language')));
        // 查询对话信息是否存在
        $chatInfo = $type == 1 ?
            ChatModel::where(['_id' => trim($request->input('chat_id'))])->first() :
            CustomerModel::where(['_id' => trim($request->input('chat_id'))])->first();
        if (!$chatInfo) {
            // 对话不存在
            return HelperService::getInstance()->errorService(trans('validation.exists', ['attribute' => 'chatInfo']));
        }

        // 更新当前用户的语种
        ChatModel::where(['_id' => trim($request->input('chat_id'))])->update(['language' => $lan]);

        // 获取语种模板
        $langInfo = ChatModel::where([
            'type' => 1,
            'language' => $lan
        ])->first();
        if (!$langInfo) {
            $langInfo = ChatModel::where([
                'type' => 1,
                'language' => 'hk'
            ])->first();
        }

        // 根据模板获取一个随机客服
        $customerList = CustomerModel::where('chat_id', $langInfo->id)->get();
        $customerInfo = $customerList[mt_rand(0, count($customerList) - 1)];
        $customerInfo = array_merge($langInfo->toArray(), $customerInfo->toArray());

        return $this->success([
            'chatInfo' => $chatInfo,
            'wsAddress' => $request->getHost() . '/ws/',
            'customer' => $customerInfo
        ]);
    }

    public function zipFolder(Request $request): BinaryFileResponse|JsonResponse
    {
        // 需要压缩的文件
        $fileName = $request->input('file_name');

        $folderPath =   '/www/wwwroot/lrobot/cache/' . $fileName;

        // 压缩文件名
        $zipFileName = $fileName . '.zip';

        $zip = new ZipArchive;

        // 创建一个临时文件来存储ZIP文件
        $tempFile = tempnam(sys_get_temp_dir(), $zipFileName);

        // 打开创建的ZIP文件
        if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {

            // 设置要压缩的目录
            $directoryToZip = $folderPath; // 调整这个路径到你想要压缩的目录

            // 这个函数用于递归添加目录到zip文件
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directoryToZip),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // 跳过目录（它们会被递归添加）
                if (!$file->isDir()) {
                    // 获取真实路径
                    $filePath = $file->getRealPath();

                    if ($filePath !== false) {
                        // 获取相对路径
                        $relativePath = substr($filePath, strlen($directoryToZip));
                        // 添加到zip文件中
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            // 关闭ZIP文件
            $zip->close();

            // 返回ZIP文件作为下载
            return response()->download($tempFile, $zipFileName, ['Content-Type' => 'application/zip'])->deleteFileAfterSend(true);
        } else {
            dd("无法创建ZIP文件");
        }
    }


}
