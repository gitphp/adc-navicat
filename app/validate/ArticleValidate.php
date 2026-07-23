<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 文章验证器
 */
class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     * @var array
     */
    protected $rule = [
        'title'           => 'require|max:255',
        'subtitle'        => 'max:128',
        'art_cover'       => 'max:500',
        'art_content'     => 'require',
        'content_type'    => 'integer|in:1,2,3',
        'summary'         => 'max:512',
        'category_id'     => 'integer|egt:0',
        'tag_ids'         => 'array',
        'author_id'       => 'integer|egt:0',
        'author_name'     => 'max:16',
        'source'          => 'max:64',
        'source_url'      => 'max:512|url',
        'art_status'      => 'integer|in:1,2,3,4,5,6,7',
        'is_top'          => 'integer|in:0,1',
        'is_original'     => 'integer|in:0,1',
        'is_commentable'  => 'integer|in:0,1',
        'seo_title'       => 'max:255',
        'seo_keywords'    => 'max:255',
        'seo_description' => 'max:512',
        'extra_fields'    => 'array',
        'reject_reason'   => 'max:512',
    ];
    
    /**
     * 定义验证消息
     * @var array
     */
    protected $message = [
        'title.require'           => '文章标题不能为空',
        'title.max'               => '文章标题不能超过255个字符',
        'subtitle.max'            => '副标题不能超过128个字符',
        'art_cover.max'           => '封面图URL不能超过500个字符',
        'art_content.require'     => '文章内容不能为空',
        'content_type.integer'    => '内容类型必须是整数',
        'content_type.in'         => '内容类型值不正确',
        'summary.max'             => '文章摘要不能超过512个字符',
        'category_id.integer'     => '分类ID必须是整数',
        'category_id.egt'         => '分类ID不能为负数',
        'tag_ids.array'           => '标签ID必须是数组',
        'author_id.integer'       => '作者ID必须是整数',
        'author_id.egt'           => '作者ID不能为负数',
        'author_name.max'         => '作者姓名不能超过16个字符',
        'source.max'              => '文章来源不能超过64个字符',
        'source_url.max'          => '原文链接不能超过512个字符',
        'source_url.url'          => '原文链接格式不正确',
        'art_status.integer'      => '状态必须是整数',
        'art_status.in'           => '状态值不正确',
        'is_top.integer'          => '是否置顶必须是整数',
        'is_top.in'               => '是否置顶值不正确',
        'is_original.integer'     => '是否原创必须是整数',
        'is_original.in'          => '是否原创值不正确',
        'is_commentable.integer'  => '是否允许评论必须是整数',
        'is_commentable.in'       => '是否允许评论值不正确',
        'seo_title.max'           => 'SEO标题不能超过255个字符',
        'seo_keywords.max'        => 'SEO关键词不能超过255个字符',
        'seo_description.max'     => 'SEO描述不能超过512个字符',
        'extra_fields.array'      => '扩展字段必须是数组',
        'reject_reason.max'       => '驳回原因不能超过512个字符',
    ];
    
    /**
     * 定义场景
     * @var array
     */
    protected $scene = [
        'add'   => ['title', 'subtitle', 'art_cover', 'art_content', 'content_type', 'summary', 'category_id', 'tag_ids', 'author_id', 'author_name', 'source', 'source_url', 'art_status', 'is_top', 'is_original', 'is_commentable', 'seo_title', 'seo_keywords', 'seo_description', 'extra_fields'],
        'edit'  => ['title', 'subtitle', 'art_cover', 'art_content', 'content_type', 'summary', 'category_id', 'tag_ids', 'author_id', 'author_name', 'source', 'source_url', 'art_status', 'is_top', 'is_original', 'is_commentable', 'seo_title', 'seo_keywords', 'seo_description', 'extra_fields'],
        'review' => ['art_status', 'reject_reason'],
    ];
}
