<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use think\facade\Session;

/**
 * 后台首页控制器
 */
class Index extends BaseController
{
    /**
     * 后台首页
     * @return \think\Response
     */
    public function index()
    {
        $userInfo = Session::get('user_info');
        
        return view('index/index', [
            'user_info' => $userInfo,
        ]);
    }

    /**
     * 测试方法
     * @param string $name
     * @return string
     */
    public function hello($name = 'World')
    {
        return 'Hello, ' . $name . '!';
    }
}
