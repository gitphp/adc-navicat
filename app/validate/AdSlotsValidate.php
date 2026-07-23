<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 广告位验证器
 */
class AdSlotsValidate extends Validate
{
    protected $rule = [
        'slot_code' => 'require|max:32|alphaDash',
        'slot_name' => 'require|max:128',
        'description' => 'max:255',
        'width' => 'integer|min:0',
        'height' => 'integer|min:0',
        'max_items' => 'integer|min:1',
        'is_system' => 'integer|in:0,1',
        'slot_status' => 'integer|in:0,1',
    ];

    protected $message = [
        'slot_code.require' => '广告位编码不能为空',
        'slot_code.max' => '广告位编码不能超过32个字符',
        'slot_code.alphaDash' => '广告位编码只能包含字母、数字、下划线和破折号',
        'slot_name.require' => '广告位名称不能为空',
        'slot_name.max' => '广告位名称不能超过128个字符',
        'description.max' => '广告位描述不能超过255个字符',
        'width.integer' => '宽度必须为整数',
        'width.min' => '宽度不能为负数',
        'height.integer' => '高度必须为整数',
        'height.min' => '高度不能为负数',
        'max_items.integer' => '最大展示数量必须为整数',
        'max_items.min' => '最大展示数量至少为1',
        'is_system.integer' => '系统预设必须为整数',
        'is_system.in' => '系统预设只能是0或1',
        'slot_status.integer' => '状态必须为整数',
        'slot_status.in' => '状态只能是0或1',
    ];

    // 添加场景
    protected $scene = [
        'add' => ['slot_code', 'slot_name', 'width', 'height', 'max_items', 'is_system', 'slot_status'],
        'edit' => ['slot_code', 'slot_name', 'width', 'height', 'max_items', 'slot_status'],
    ];
}