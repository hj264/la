<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('registerChat', [PublicController::class, 'registerChat']); // 对话注册
Route::post('loginChat', [PublicController::class, 'loginChat']); // 登陆对话
Route::post('getSocketAddress', [PublicController::class, 'getSocketAddress']); // 获取邮箱验证码
Route::post('forgetPassword', [PublicController::class, 'forgetPassword']); // 忘记密码
Route::post('getBanner', [PublicController::class, 'getBanner']); // 获取banner
Route::post('getVipList', [PublicController::class, 'getVipList']); // 获取vip 说明
Route::post('uploadImg', [PublicController::class, 'uploadImg']); // 上传图片
Route::post('getUserInfo', [PublicController::class, 'getUserInfo']); // 上传图片
Route::post('getLangCode', [PublicController::class, 'getLangCode']); // 上传图片
Route::post('myVideo', [PublicController::class, 'myVideo']); // 更新用户信息
Route::get('test', [PublicController::class, 'test']); // 更新用户信息
Route::post('webhook', [WebhookController::class, 'webhook']); // webhook
Route::get('zipFolder', [PublicController::class, 'zipFolder']); // webhook
Route::get('deleteCacheFile', [PublicController::class, 'deleteCacheFile']); // 删除文件夹
Route::post('sendTwo', [PublicController::class, 'sendTwo']); // number two message
