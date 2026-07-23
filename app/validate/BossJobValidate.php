<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 招聘职位验证器
 */
class BossJobValidate extends Validate
{
    /**
     * 定义验证规则
     * @var array
     */
    protected $rule = [
        'job_title'    => 'require|max:64',
        'department'   => 'max:64',
        'workplace'    => 'max:128',
        'experience'   => 'max:64',
        'education'    => 'max:64',
        'salary_range' => 'max:64',
        'description'  => 'max:65535',
        'requirements' => 'max:65535',
        'benefits'     => 'max:65535',
        'is_hot'       => 'in:0,1',
        'job_status'   => 'in:1,2,3',
        'expire_at'    => 'date',
        'job_sort'     => 'number',
    ];
    
    /**
     * 定义错误信息
     * @var array
     */
    protected $message = [
        'job_title.require'    => '职位名称不能为空',
        'job_title.max'        => '职位名称不能超过64个字符',
        'department.max'       => '所属部门不能超过64个字符',
        'workplace.max'        => '工作地点不能超过128个字符',
        'experience.max'       => '经验要求不能超过64个字符',
        'education.max'        => '学历要求不能超过64个字符',
        'salary_range.max'     => '薪资范围不能超过64个字符',
        'description.max'      => '职位描述不能超过65535个字符',
        'requirements.max'     => '任职要求不能超过65535个字符',
        'benefits.max'         => '福利待遇不能超过65535个字符',
        'is_hot.in'            => '是否急聘值不正确',
        'job_status.in'        => '状态值不正确',
        'expire_at.date'       => '过期时间格式不正确',
        'job_sort.number'      => '排序权重必须是数字',
    ];
    
    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add'  => ['job_title', 'department', 'workplace', 'experience', 'education', 'salary_range', 'description', 'requirements', 'benefits', 'is_hot', 'job_status', 'expire_at', 'job_sort'],
        'edit' => ['job_title', 'department', 'workplace', 'experience', 'education', 'salary_range', 'description', 'requirements', 'benefits', 'is_hot', 'job_status', 'expire_at', 'job_sort'],
    ];
}