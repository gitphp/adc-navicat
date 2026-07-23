<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 友情链接模型
 * @property int $id 主键ID
 * @property string $link_name 网站名称
 * @property string $link_url 网站链接
 * @property string $link_logo 网站Logo
 * @property string $link_desc 网站描述
 * @property int $link_sort 排序权重
 * @property int $link_status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class FriendLinks extends Model
{
    // 设置表名
    protected $name = 'friend_links';

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
    public function getLinkStatusTextAttribute(): string
    {
        return $this->link_status == 1 ? '启用' : '禁用';
    }

    /**
     * 获取状态标签样式
     * @return string
     */
    public function getLinkStatusClassAttribute(): string
    {
        return $this->link_status == 1 ? 'layui-btn layui-btn-xs layui-btn-normal' : 'layui-btn layui-btn-xs layui-btn-danger';
    }
}