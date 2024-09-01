<?php

namespace App\Service;


class HelperService
{

    // 单例容器
    private static ?HelperService $instance = null;

    // 基类构造方法
    function __construct()
    {
    }

    static function getInstance(): HelperService
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // 生成随机字符
    function GetRandStr($length): string
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }

    function errorService(string $msg, array $data = []): array
    {
        return [
            'status' => false,
            'msg' => $msg,
            'data' => $data
        ];
    }

    function successService(array $data, string $msg = ''): array
    {
        return [
            'status' => true,
            'msg' => $msg,
            'data' => $data
        ];
    }


    // 获取目录
    function getDir(string $dir): array
    {
        $fileList = scandir($dir);
        $nations = [];
        foreach ($fileList as $item) {
            $filePath = $dir . '/' . $item;
            if ($item != '.' && $item != '..' && is_dir($filePath)) {
                $nations[$item] = $item;
            }
        }
        return $nations;
    }


    function getLang(): array
    {
        $current['en'] = "英语";
        $current['hk'] = '中文繁体';
        $current['zh'] = '中文简体';
        $current['lo'] = '老挝语';
        $current['ja'] = '日语';
        $current['ko'] = '韩语';
        $current['th'] = '泰语';
        $current['es'] = '西班牙';
        $current['de'] = '德国';
        $current['pt'] = '葡萄牙';
        $current['cm'] = '出码语种模板';
        return $current;


    }


}
