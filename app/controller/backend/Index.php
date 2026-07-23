<?php
declare (strict_types = 1);

namespace app\controller\backend;

/**
 * 后台首页控制器
 */
class Index extends BackendBase
{
    /**
     * 后台首页
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '系统管理后台';
        
        return $this->render('index/index');
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