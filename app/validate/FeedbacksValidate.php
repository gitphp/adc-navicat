<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 用户留言验证器
 */
class FeedbacksValidate extends Validate
{
    protected $rule = [
        'fb_name' => 'require|max:32',
        'fb_phone' => 'max:16',
        'fb_email' => 'max:32|email',
        'fb_company' => 'max:32',
        'fb_title' => 'require|max:128',
        'fb_content' => 'require',
        'fb_status' => 'integer|in:0,1',
        'reply_content' => 'require',
    ];

    protected $message = [
        'fb_name.require' => '联系人姓名不能为空',
        'fb_name.max' => '联系人姓名不能超过32个字符',
        'fb_phone.max' => '联系电话不能超过16个字符',
        'fb_email.max' => '邮箱不能超过32个字符',
        'fb_email.email' => '邮箱格式不正确',
        'fb_company.max' => '公司名称不能超过32个字符',
        'fb_title.require' => '留言标题不能为空',
        'fb_title.max' => '留言标题不能超过128个字符',
        'fb_content.require' => '留言内容不能为空',
        'fb_status.integer' => '状态必须为整数',
        'fb_status.in' => '状态只能是0或1',
        'reply_content.require' => '回复内容不能为空',
    ];

    // 添加场景
    protected $scene = [
        'reply' => ['reply_content'],
    ];
}