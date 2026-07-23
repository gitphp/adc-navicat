<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 分类验证器
 */
class CategoryValidate extends Validate
{
    /**
     * 定义验证规则
     * @var array
     */
    protected $rule = [
        'category_name' => 'require|max:255',
        'parent_id'     => 'integer|egt:0',
        'show_type'     => 'integer|in:0,1,2',
        'cat_status'    => 'integer|in:0,1',
        'level'         => 'integer|between:1,3',
        'sort_order'    => 'integer|egt:0',
        'description'   => 'max:512',
        'cat_remark'    => 'max:512',
    ];
    
    /**
     * 定义验证消息
     * @var array
     */
    protected $message = [
        'category_name.require' => '分类名称不能为空',
        'category_name.max'     => '分类名称不能超过255个字符',
        'parent_id.integer'     => '父级ID必须是整数',
        'parent_id.egt'         => '父级ID不能为负数',
        'show_type.integer'     => '可见性类型必须是整数',
        'show_type.in'          => '可见性类型值不正确',
        'cat_status.integer'    => '状态必须是整数',
        'cat_status.in'         => '状态值不正确',
        'level.integer'         => '级别必须是整数',
        'level.between'         => '级别只能是1-3级',
        'sort_order.integer'    => '排序必须是整数',
        'sort_order.egt'        => '排序不能为负数',
        'description.max'       => '分类描述不能超过512个字符',
        'cat_remark.max'        => '备注不能超过512个字符',
    ];
    
    /**
     * 定义场景
     * @var array
     */
    protected $scene = [
        'add'   => ['category_name', 'parent_id', 'show_type', 'cat_status', 'level', 'sort_order', 'description', 'cat_remark'],
        'edit'  => ['category_name', 'parent_id', 'show_type', 'cat_status', 'level', 'sort_order', 'description', 'cat_remark'],
    ];
}
