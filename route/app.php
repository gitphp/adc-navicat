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
    // 首页
    Route::get('index', 'backend.index/index');
    Route::get('index/hello/:name', 'backend.index/hello');
    
    // 角色管理
    Route::get('role', 'backend.role/index');
    Route::get('role/list', 'backend.role/list');
    Route::get('role/add', 'backend.role/add');
    Route::post('role/save', 'backend.role/save');
    Route::get('role/edit', 'backend.role/edit');
    Route::post('role/update', 'backend.role/update');
    Route::post('role/del', 'backend.role/del');
    Route::post('role/status', 'backend.role/status');
    
    // 权限管理
    Route::get('permission', 'backend.permission/index');
    Route::get('permission/list', 'backend.permission/list');
    Route::get('permission/tree', 'backend.permission/tree');
    Route::get('permission/add', 'backend.permission/add');
    Route::post('permission/save', 'backend.permission/save');
    Route::get('permission/edit', 'backend.permission/edit');
    Route::post('permission/update', 'backend.permission/update');
    Route::post('permission/del', 'backend.permission/del');
    Route::post('permission/status', 'backend.permission/status');
    
    // 菜单管理
    Route::get('menu', 'backend.menu/index');
    Route::get('menu/list', 'backend.menu/list');
    Route::get('menu/tree', 'backend.menu/tree');
    Route::get('menu/add', 'backend.menu/add');
    Route::post('menu/save', 'backend.menu/save');
    Route::get('menu/edit', 'backend.menu/edit');
    Route::post('menu/update', 'backend.menu/update');
    Route::post('menu/del', 'backend.menu/del');
    Route::post('menu/status', 'backend.menu/status');
})->middleware(\app\middleware\Auth::class);

Route::get('think', function () {
    return 'hello,ThinkPHP8!';
});
