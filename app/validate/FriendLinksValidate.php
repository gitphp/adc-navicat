<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 友情链接验证器
 */
class FriendLinksValidate extends Validate
{
    protected $rule = [
        'link_name' => 'require|max:32',
        'link_url' => 'require|max:512',
        'link_logo' => 'max:512',
        'link_desc' => 'max:255',
        'link_sort' => 'integer|min:0',
        'link_status' => 'integer|in:0,1',
    ];

    protected $message = [
        'link_name.require' => '网站名称不能为空',
        'link_name.max' => '网站名称不能超过32个字符',
        'link_url.require' => '网站链接不能为空',
        'link_url.max' => '网站链接不能超过512个字符',
        'link_logo.max' => '网站Logo不能超过512个字符',
        'link_desc.max' => '网站描述不能超过255个字符',
        'link_sort.integer' => '排序权重必须为整数',
        'link_sort.min' => '排序权重不能为负数',
        'link_status.integer' => '状态必须为整数',
        'link_status.in' => '状态只能是0或1',
    ];

    // 添加场景
    protected $scene = [
        'add' => ['link_name', 'link_url', 'link_sort', 'link_status'],
        'edit' => ['link_name', 'link_url', 'link_sort', 'link_status'],
    ];
}