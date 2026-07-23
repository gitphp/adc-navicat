<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 权限验证器
 */
class PermissionValidate extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'per_name'   => 'require|min:2|max:64',
        'per_code'   => 'require|min:2|max:128|alphaDash',
        'per_type'   => 'require|in:menu,button,api',
        'per_path'   => 'max:255',
        'per_method' => 'in:GET,POST,PUT,DELETE,PATCH,',
        'per_icon'   => 'max:64',
        'per_sort'   => 'number|between:0,9999',
        'per_status' => 'require|in:0,1',
    ];

    /**
     * 验证提示
     * @var array
     */
    protected $message = [
        'per_name.require'  => '请输入权限名称',
        'per_name.min'      => '权限名称长度不能少于2个字符',
        'per_name.max'      => '权限名称长度不能超过64个字符',
        'per_code.require'  => '请输入权限标识',
        'per_code.min'      => '权限标识长度不能少于2个字符',
        'per_code.max'      => '权限标识长度不能超过128个字符',
        'per_code.alphaDash'=> '权限标识只能包含字母、数字、下划线和短横线',
        'per_type.require'  => '请选择权限类型',
        'per_type.in'       => '权限类型值不正确',
        'per_path.max'      => '路径长度不能超过255个字符',
        'per_method.in'     => 'HTTP方法值不正确',
        'per_icon.max'      => '图标长度不能超过64个字符',
        'per_sort.number'   => '排序号必须是数字',
        'per_sort.between'  => '排序号必须在0-9999之间',
        'per_status.require'=> '请选择权限状态',
        'per_status.in'     => '权限状态值不正确',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'add'  => ['per_name', 'per_code', 'per_type', 'per_path', 'per_method', 'per_icon', 'per_sort', 'per_status'],
        'edit' => ['per_name', 'per_code', 'per_type', 'per_path', 'per_method', 'per_icon', 'per_sort', 'per_status'],
    ];
}
