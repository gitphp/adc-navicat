<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 文章分类验证器
 */
class ArticleCategoryValidate extends Validate
{
    /**
     * 定义验证规则
     * @var array
     */
    protected $rule = [
        'cat_name'   => 'require|max:32',
        'cat_url'    => 'max:32',
        'parent_id'  => 'number',
        'cat_sort'   => 'number',
        'status'     => 'in:0,1',
        'description'=> 'max:255',
    ];
    
    /**
     * 定义错误信息
     * @var array
     */
    protected $message = [
        'cat_name.require'   => '分类名称不能为空',
        'cat_name.max'       => '分类名称不能超过32个字符',
        'cat_url.max'        => 'URL别名不能超过32个字符',
        'parent_id.number'   => '父级ID必须是数字',
        'cat_sort.number'    => '排序权重必须是数字',
        'status.in'          => '状态值不正确',
        'description.max'    => '分类描述不能超过255个字符',
    ];
    
    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add'  => ['cat_name', 'cat_url', 'parent_id', 'cat_sort', 'status', 'description'],
        'edit' => ['cat_name', 'cat_url', 'parent_id', 'cat_sort', 'status', 'description'],
    ];
}