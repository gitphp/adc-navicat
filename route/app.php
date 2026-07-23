<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

// 旧登录路径重定向到新路径
Route::get('login', function() {
    return redirect('/backend/login');
});

// 后台登录路由
Route::get('backend/login', 'backend.login/index');
Route::post('backend/login/doLogin', 'backend.login/doLogin');
Route::get('backend/login/logout', 'backend.login/logout');
Route::get('backend/login/captcha', 'backend.login/captcha');
Route::get('backend/login/check', 'backend.login/check');

// 后台首页路由（需认证）
Route::group('backend', function() {
    Route::get('index', 'backend.index/index');
    Route::get('index/hello/:name', 'backend.index/hello');
})->middleware(\app\middleware\Auth::class);

Route::get('think', function () {
    return 'hello,ThinkPHP8!';
});
