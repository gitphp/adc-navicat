<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 书签验证器
 */
class BookMarkValidate extends Validate
{
    protected $rule = [
        'short_title' => 'require|max:16',
        'book_title' => 'require|max:128',
        'book_url' => 'require|url|max:2048',
        'book_favicon' => 'max:512',
        'book_desc' => 'max:1024',
        'sort_order' => 'integer|min:0',
        'status' => 'integer|in:0,1,2',
        'is_bold' => 'integer|in:0,1',
        'category_id' => 'integer',
    ];

    protected $message = [
        'short_title.require' => '短标题不能为空',
        'short_title.max' => '短标题不能超过16个字符',
        'book_title.require' => '长标题不能为空',
        'book_title.max' => '长标题不能超过128个字符',
        'book_url.require' => '链接地址不能为空',
        'book_url.url' => '链接地址格式不正确',
        'book_url.max' => '链接地址不能超过2048个字符',
        'book_favicon.max' => '网站图标URL不能超过512个字符',
        'book_desc.max' => '书签描述不能超过1024个字符',
        'sort_order.integer' => '排序权重必须为整数',
        'sort_order.min' => '排序权重不能为负数',
        'status.integer' => '状态值必须为整数',
        'status.in' => '状态值只能是0、1或2',
        'is_bold.integer' => '加粗显示必须为整数',
        'is_bold.in' => '加粗显示只能是0或1',
        'category_id.integer' => '分类ID必须为整数',
    ];

    // 添加场景
    protected $scene = [
        'add' => ['short_title', 'book_title', 'book_url', 'sort_order', 'status', 'is_bold', 'category_id'],
        'edit' => ['short_title', 'book_title', 'book_url', 'sort_order', 'status', 'is_bold', 'category_id'],
    ];
}