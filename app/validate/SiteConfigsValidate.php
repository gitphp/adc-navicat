<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 站点配置验证器
 */
class SiteConfigsValidate extends Validate
{
    protected $rule = [
        'conf_group' => 'require|max:32',
        'conf_key' => 'require|max:128',
        'conf_value' => 'max:65535',
        'conf_desc' => 'max:255',
        'input_type' => 'require|in:text,textarea,image,file,json',
        'conf_sort' => 'integer|min:0',
    ];

    protected $message = [
        'conf_group.require' => '配置分组不能为空',
        'conf_group.max' => '配置分组不能超过32个字符',
        'conf_key.require' => '配置键名不能为空',
        'conf_key.max' => '配置键名不能超过128个字符',
        'conf_value.max' => '配置值不能超过65535个字符',
        'conf_desc.max' => '配置说明不能超过255个字符',
        'input_type.require' => '输入类型不能为空',
        'input_type.in' => '输入类型只能是text,textarea,image,file,json',
        'conf_sort.integer' => '排序必须为整数',
        'conf_sort.min' => '排序不能为负数',
    ];

    // 添加场景
    protected $scene = [
        'add' => ['conf_group', 'conf_key', 'conf_desc', 'input_type', 'conf_sort'],
        'edit' => ['conf_group', 'conf_key', 'conf_desc', 'input_type', 'conf_sort'],
        'save' => ['conf_value'],
    ];
}