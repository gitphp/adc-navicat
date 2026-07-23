<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 文章主表模型
 * @property int $id 主键ID
 * @property string $title 文章标题
 * @property string $subtitle 副标题/摘要
 * @property string $art_cover 封面图URL
 * @property string $art_content 文章正文内容
 * @property int $content_type 内容类型
 * @property string $summary 文章摘要
 * @property int $category_id 分类ID
 * @property array $tag_ids 标签ID列表
 * @property int $author_id 作者用户ID
 * @property string $author_name 作者姓名
 * @property string $source 文章来源
 * @property string $source_url 原文链接
 * @property int $art_status 状态
 * @property int $is_top 是否置顶
 * @property int $is_original 是否原创
 * @property int $is_commentable 是否允许评论
 * @property string $seo_title SEO标题
 * @property string $seo_keywords SEO关键词
 * @property string $seo_description SEO描述
 * @property array $extra_fields 扩展字段
 * @property int $view_count 浏览量
 * @property int $like_count 点赞量
 * @property int $collect_count 收藏量
 * @property int $share_count 分享量
 * @property int $comment_count 评论量
 * @property string $published_at 发布时间
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 软删除时间
 * @property int $reviewer_id 审核人ID
 * @property string $reviewed_at 审核时间
 * @property string $reject_reason 驳回原因
 */
class ConArticle extends Model
{
    use SoftDelete;
    
    // 设置表名
    protected $name = 'articles';
    
    // 设置主键
    protected $pk = 'id';
    
    // 软删除字段
    protected $deleteTime = 'deleted_at';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    
    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';
    
    // JSON字段
    protected $json = ['tag_ids', 'extra_fields'];
    protected $jsonAssoc = true;
    
    // 类型转换
    protected $type = [
        'id'             => 'integer',
        'content_type'   => 'integer',
        'category_id'    => 'integer',
        'author_id'      => 'integer',
        'art_status'     => 'integer',
        'is_top'         => 'integer',
        'is_original'    => 'integer',
        'is_commentable' => 'integer',
        'view_count'     => 'integer',
        'like_count'     => 'integer',
        'collect_count'  => 'integer',
        'share_count'    => 'integer',
        'comment_count'  => 'integer',
        'reviewer_id'    => 'integer',
    ];
    
    // 内容类型映射
    const CONTENT_TYPE_RICH_TEXT = 1;  // 富文本
    const CONTENT_TYPE_MARKDOWN = 2;   // Markdown
    const CONTENT_TYPE_PLAIN_TEXT = 3; // 纯文本
    
    // 文章状态映射
    const STATUS_DRAFT = 1;      // 草稿
    const STATUS_PENDING_REVIEW = 2; // 待审核
    const STATUS_APPROVED = 3;    // 审核通过
    const STATUS_PUBLISHED = 4;   // 已发布
    const STATUS_OFFLINE = 5;     // 已下线
    const STATUS_REJECTED = 6;    // 审核驳回
    const STATUS_TRASH = 7;       // 回收站
    
    // 是否置顶映射
    const IS_TOP_NO = 0;
    const IS_TOP_YES = 1;
    
    // 是否原创映射
    const IS_ORIGINAL_NO = 0;
    const IS_ORIGINAL_YES = 1;
    
    // 是否允许评论映射
    const IS_COMMENTABLE_NO = 0;
    const IS_COMMENTABLE_YES = 1;
    
    /**
     * 获取内容类型文本
     * @param int $type
     * @return string
     */
    public static function getContentTypeText(int $type): string
    {
        $options = [
            self::CONTENT_TYPE_RICH_TEXT => '富文本',
            self::CONTENT_TYPE_MARKDOWN => 'Markdown',
            self::CONTENT_TYPE_PLAIN_TEXT => '纯文本',
        ];
        return $options[$type] ?? '未知';
    }
    
    /**
     * 获取内容类型选项
     * @return array
     */
    public static function getContentTypeOptions(): array
    {
        return [
            ['value' => self::CONTENT_TYPE_RICH_TEXT, 'label' => '富文本'],
            ['value' => self::CONTENT_TYPE_MARKDOWN, 'label' => 'Markdown'],
            ['value' => self::CONTENT_TYPE_PLAIN_TEXT, 'label' => '纯文本'],
        ];
    }
    
    /**
     * 获取状态文本
     * @param int $status
     * @return string
     */
    public static function getStatusText(int $status): string
    {
        $options = [
            self::STATUS_DRAFT => '草稿',
            self::STATUS_PENDING_REVIEW => '待审核',
            self::STATUS_APPROVED => '审核通过',
            self::STATUS_PUBLISHED => '已发布',
            self::STATUS_OFFLINE => '已下线',
            self::STATUS_REJECTED => '审核驳回',
            self::STATUS_TRASH => '回收站',
        ];
        return $options[$status] ?? '未知';
    }
    
    /**
     * 获取状态选项
     * @return array
     */
    public static function getStatusOptions(): array
    {
        return [
            ['value' => self::STATUS_DRAFT, 'label' => '草稿'],
            ['value' => self::STATUS_PENDING_REVIEW, 'label' => '待审核'],
            ['value' => self::STATUS_APPROVED, 'label' => '审核通过'],
            ['value' => self::STATUS_PUBLISHED, 'label' => '已发布'],
            ['value' => self::STATUS_OFFLINE, 'label' => '已下线'],
            ['value' => self::STATUS_REJECTED, 'label' => '审核驳回'],
            ['value' => self::STATUS_TRASH, 'label' => '回收站'],
        ];
    }
    
    /**
     * 获取来源选项
     * @return array
     */
    public static function getSourceOptions(): array
    {
        return [
            ['value' => '原创', 'label' => '原创'],
            ['value' => '转载', 'label' => '转载'],
            ['value' => '翻译', 'label' => '翻译'],
        ];
    }
    
    /**
     * 关联分类
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
    /**
     * 关联作者
     * @return \think\model\relation\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
    
    /**
     * 关联审核人
     * @return \think\model\relation\BelongsTo
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }
    
    /**
     * 增加浏览量
     * @param int $id 文章ID
     * @return bool
     */
    public static function incrementViewCount(int $id): bool
    {
        return self::where('id', $id)->inc('view_count')->update() !== false;
    }
    
    /**
     * 增加点赞量
     * @param int $id 文章ID
     * @return bool
     */
    public static function incrementLikeCount(int $id): bool
    {
        return self::where('id', $id)->inc('like_count')->update() !== false;
    }
    
    /**
     * 增加收藏量
     * @param int $id 文章ID
     * @return bool
     */
    public static function incrementCollectCount(int $id): bool
    {
        return self::where('id', $id)->inc('collect_count')->update() !== false;
    }
    
    /**
     * 增加分享量
     * @param int $id 文章ID
     * @return bool
     */
    public static function incrementShareCount(int $id): bool
    {
        return self::where('id', $id)->inc('share_count')->update() !== false;
    }
    
    /**
     * 增加评论量
     * @param int $id 文章ID
     * @return bool
     */
    public static function incrementCommentCount(int $id): bool
    {
        return self::where('id', $id)->inc('comment_count')->update() !== false;
    }
    
    /**
     * 获取指定状态的文章数量
     * @param int $status
     * @return int
     */
    public static function getCountByStatus(int $status): int
    {
        return self::where('art_status', $status)->count();
    }
}
