<?php

use App\Admin\Controllers\CustomerController;
use App\Admin\Controllers\FryController;
use App\Admin\Controllers\SettingChatController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('/setting_chat', SettingChatController::class); // 客服设置
    $router->resource('/fry', FryController::class); // 鱼苗
    $router->resource('/customer', CustomerController::class); // 客服列表
});
