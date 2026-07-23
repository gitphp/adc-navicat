<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\helper\PermissionHelper;
use think\facade\Session;

/**
 * 后台基础控制器
 * 所有后台控制器应继承此类
 */
class BackendBase extends BaseController
{
    /**
     * 用户信息
     * @var array
     */
    protected $userInfo = [];
    
    /**
     * 菜单树
     * @var array
     */
    protected $menuTree = [];
    
    /**
     * 页面标题
     * @var string
     */
    protected $title = '';
    
    /**
     * 初始化
     */
    protected function initialize()
    {
        parent::initialize();
        
        // 获取用户信息
        $this->userInfo = Session::get('user_info', []);
        
        // 获取菜单树
        $this->menuTree = PermissionHelper::getCurrentMenuTree();
        
        // 如果菜单树为空，生成默认菜单（超级管理员）
        if (empty($this->menuTree)) {
            $this->menuTree = $this->getDefaultMenuTree();
        }
    }
    
    /**
     * 渲染页面
     * @param string $template 模板名称
     * @param array $data 模板数据
     * @return \think\Response
     */
    protected function render(string $template, array $data = []): \think\Response
    {
        // 合并公共数据
        $viewData = array_merge([
            'user_info' => $this->userInfo,
            'menu_tree' => $this->menuTree,
            'title'     => $this->title,
        ], $data);
        
        // 渲染子页面内容
        ob_start();
        view($template, $viewData)->send();
        $content = ob_get_clean();
        
        // 渲染主布局
        return view('layout/main', array_merge($viewData, [
            'content' => $content,
        ]));
    }
    
    /**
     * 获取默认菜单树（超级管理员）
     * @return array
     */
    protected function getDefaultMenuTree(): array
    {
        return [
            [
                'id' => 1,
                'menu_name' => '系统管理',
                'menu_url' => '',
                'menu_icon' => 'layui-icon-set',
                'parent_id' => 0,
                'children' => [
                    [
                        'id' => 11,
                        'menu_name' => '用户管理',
                        'menu_url' => '/backend/user/index',
                        'menu_icon' => 'layui-icon-user',
                        'parent_id' => 1,
                    ],
                    [
                        'id' => 12,
                        'menu_name' => '角色管理',
                        'menu_url' => '/backend/role/index',
                        'menu_icon' => 'layui-icon-group',
                        'parent_id' => 1,
                    ],
                    [
                        'id' => 13,
                        'menu_name' => '权限管理',
                        'menu_url' => '/backend/permission/index',
                        'menu_icon' => 'layui-icon-lock',
                        'parent_id' => 1,
                    ],
                    [
                        'id' => 14,
                        'menu_name' => '菜单管理',
                        'menu_url' => '/backend/menu/index',
                        'menu_icon' => 'layui-icon-menu',
                        'parent_id' => 1,
                    ],
                ],
            ],
            [
                'id' => 2,
                'menu_name' => '内容管理',
                'menu_url' => '',
                'menu_icon' => 'layui-icon-file',
                'parent_id' => 0,
                'children' => [
                    [
                        'id' => 21,
                        'menu_name' => '分类管理',
                        'menu_url' => '/backend/category/index',
                        'menu_icon' => 'layui-icon-list',
                        'parent_id' => 2,
                    ],
                    [
                        'id' => 22,
                        'menu_name' => '文章管理',
                        'menu_url' => '/backend/article/index',
                        'menu_icon' => 'layui-icon-read',
                        'parent_id' => 2,
                    ],
                ],
            ],
            [
                'id' => 3,
                'menu_name' => '首页',
                'menu_url' => '/backend/index/index',
                'menu_icon' => 'layui-icon-home',
                'parent_id' => 0,
            ],
        ];
    }
}