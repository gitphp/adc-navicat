<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 登录验证器
 */
class LoginValidate extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'account'   => 'require|min:3|max:64',
        'password'  => 'require|min:6|max:64',
        'captcha'   => 'captcha',
        'remember'  => 'boolean',
    ];

    /**
     * 验证提示
     * @var array
     */
    protected $message = [
        'account.require'  => '请输入账号',
        'account.min'      => '账号长度不能少于3个字符',
        'account.max'      => '账号长度不能超过64个字符',
        'password.require' => '请输入密码',
        'password.min'     => '密码长度不能少于6个字符',
        'password.max'     => '密码长度不能超过64个字符',
        'captcha.captcha'  => '验证码错误',
        'remember.boolean' => '记住我参数错误',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'login'  => ['account', 'password'],
        'captcha_login' => ['account', 'password', 'captcha'],
    ];
}
