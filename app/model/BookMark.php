<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 书签模型
 * @property int $id 主键ID
 * @property int $category_id 所属分类ID
 * @property string $short_title 书签短标题
 * @property string $book_title 书签长标题
 * @property string $book_url 书签链接地址
 * @property string $book_favicon 网站图标URL
 * @property string $book_desc 书签描述/备注
 * @property int $sort_order 排序权重
 * @property int $status 状态
 * @property int $is_bold 是否加粗显示
 * @property string $created_at 创建时间
 * @property int $created_by 创建人
 * @property string $updated_at 更新时间
 */
class BookMark extends Model
{
    // 设置表名
    protected $name = 'book_mark';

    // 设置主键
    protected $pk = 'id';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 获取状态文本
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        $statusMap = [
            0 => '隐藏',
            1 => '正常',
            2 => '失效',
        ];
        return $statusMap[$this->status] ?? '未知';
    }

    /**
     * 获取分类信息
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * 获取分类名称
     * @return string
     */
    public function getCategoryNameAttribute(): string
    {
        if ($this->category_id == 0) {
            return '默认书签栏';
        }
        $category = $this->category;
        return $category ? $category->category_name : '未知分类';
    }

    /**
     * 获取加粗状态文本
     * @return string
     */
    public function getIsBoldTextAttribute(): string
    {
        return $this->is_bold == 1 ? '是' : '否';
    }
}