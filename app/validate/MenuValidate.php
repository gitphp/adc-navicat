<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 菜单验证器
 */
class MenuValidate extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'menu_name'       => 'require|min:2|max:64',
        'menu_icon'       => 'max:64',
        'menu_path'       => 'max:255',
        'component'       => 'max:255',
        'permission_code' => 'max:128',
        'menu_sort'       => 'number|between:0,9999',
        'menu_status'     => 'require|in:0,1',
    ];

    /**
     * 验证提示
     * @var array
     */
    protected $message = [
        'menu_name.require'       => '请输入菜单名称',
        'menu_name.min'           => '菜单名称长度不能少于2个字符',
        'menu_name.max'           => '菜单名称长度不能超过64个字符',
        'menu_icon.max'           => '图标长度不能超过64个字符',
        'menu_path.max'           => '路径长度不能超过255个字符',
        'component.max'           => '组件路径长度不能超过255个字符',
        'permission_code.max'     => '权限标识长度不能超过128个字符',
        'menu_sort.number'        => '排序号必须是数字',
        'menu_sort.between'       => '排序号必须在0-9999之间',
        'menu_status.require'     => '请选择菜单状态',
        'menu_status.in'          => '菜单状态值不正确',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'add'  => ['menu_name', 'menu_icon', 'menu_path', 'component', 'permission_code', 'menu_sort', 'menu_status'],
        'edit' => ['menu_name', 'menu_icon', 'menu_path', 'component', 'permission_code', 'menu_sort', 'menu_status'],
    ];
}
