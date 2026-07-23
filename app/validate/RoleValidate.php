<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 角色验证器
 */
class RoleValidate extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'role_name'   => 'require|min:2|max:64',
        'role_code'   => 'require|min:2|max:64|alphaDash',
        'role_type'   => 'require|in:1,2',
        'role_sort'   => 'number|between:0,9999',
        'data_scope'  => 'require|in:1,2,3,4,5',
        'role_status' => 'require|in:0,1',
        'role_remark' => 'max:512',
    ];

    /**
     * 验证提示
     * @var array
     */
    protected $message = [
        'role_name.require'  => '请输入角色名称',
        'role_name.min'      => '角色名称长度不能少于2个字符',
        'role_name.max'      => '角色名称长度不能超过64个字符',
        'role_code.require'  => '请输入角色标识',
        'role_code.min'      => '角色标识长度不能少于2个字符',
        'role_code.max'      => '角色标识长度不能超过64个字符',
        'role_code.alphaDash'=> '角色标识只能包含字母、数字、下划线和短横线',
        'role_type.require'  => '请选择角色类型',
        'role_type.in'       => '角色类型值不正确',
        'role_sort.number'   => '排序号必须是数字',
        'role_sort.between'  => '排序号必须在0-9999之间',
        'data_scope.require' => '请选择数据权限范围',
        'data_scope.in'      => '数据权限范围值不正确',
        'role_status.require'=> '请选择角色状态',
        'role_status.in'     => '角色状态值不正确',
        'role_remark.max'    => '角色备注长度不能超过512个字符',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'add'  => ['role_name', 'role_code', 'role_type', 'role_sort', 'data_scope', 'role_status', 'role_remark'],
        'edit' => ['role_name', 'role_code', 'role_type', 'role_sort', 'data_scope', 'role_status', 'role_remark'],
    ];
}
