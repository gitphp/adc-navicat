<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 广告位模型
 * @property int $id 主键ID
 * @property string $slot_code 广告位编码
 * @property string $slot_name 广告位名称
 * @property string $description 广告位描述
 * @property int $width 广告位宽度
 * @property int $height 广告位高度
 * @property int $max_items 最大展示数量
 * @property int $is_system 是否系统预设
 * @property int $slot_status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AdSlots extends Model
{
    use SoftDelete;

    // 设置表名
    protected $name = 'ad_slots';

    // 设置主键
    protected $pk = 'id';

    // 软删除字段
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;

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
    public function getSlotStatusTextAttribute(): string
    {
        $statusMap = [
            0 => '禁用',
            1 => '启用',
        ];
        return $statusMap[$this->slot_status] ?? '未知';
    }

    /**
     * 获取系统预设文本
     * @return string
     */
    public function getIsSystemTextAttribute(): string
    {
        return $this->is_system == 1 ? '是' : '否';
    }

    /**
     * 获取尺寸文本
     * @return string
     */
    public function getSizeTextAttribute(): string
    {
        if ($this->width > 0 && $this->height > 0) {
            return "{$this->width} × {$this->height}";
        }
        return '未设置';
    }
}