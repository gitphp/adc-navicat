<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 广告位主表模型
 * @property int $id 主键ID
 * @property string $ad_title 广告标题
 * @property string $subtitle 广告副标题/描述
 * @property string $cover_url 广告封面图URL
 * @property string $cover_mobile 移动端封面图
 * @property string $cover_thumb 缩略图
 * @property string $video_url 视频广告URL
 * @property int $link_type 跳转类型
 * @property string $link_url 跳转链接地址
 * @property array $link_params 跳转参数
 * @property string $app_id 小程序AppId
 * @property string $app_path 小程序路径
 * @property string $position_code 广告位编码
 * @property int $platform 投放平台
 * @property int $device_type 设备类型
 * @property int $target_user_type 用户定向
 * @property array $target_user_group_ids 目标用户组ID列表
 * @property array $target_region 目标地区
 * @property string $start_time 投放开始时间
 * @property string $end_time 投放结束时间
 * @property int $show_time_type 展示时间类型
 * @property array $time_slots 自定义时间段
 * @property array $weekdays 投放星期
 * @property int $sort 排序权重
 * @property int $display_frequency 展示频率
 * @property int $daily_impression_limit 每日展示次数限制
 * @property int $daily_click_limit 每日点击次数限制
 * @property float $budget 预算金额
 * @property int $cost_type 计费方式
 * @property float $bid_price 出价金额
 * @property int $status 状态
 * @property int $audit_status 审核状态
 * @property int $reviewer_id 审核人ID
 * @property string $reviewed_at 审核时间
 * @property string $reject_reason 驳回原因
 * @property int $impression_count 展示次数
 * @property int $click_count 点击次数
 * @property float $click_rate 点击率
 * @property array $daily_stats 日统计数据缓存
 * @property int $created_by 创建人ID
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AdPositions extends Model
{
    use SoftDelete;

    // 设置表名
    protected $name = 'ad_positions';

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

    // JSON字段转换
    protected $json = ['link_params', 'target_user_group_ids', 'target_region', 'time_slots', 'weekdays', 'daily_stats'];
    protected $jsonAssoc = true;

    /**
     * 获取跳转类型文本
     * @return string
     */
    public function getLinkTypeTextAttribute(): string
    {
        $map = [
            1 => '站内链接',
            2 => '站外链接',
            3 => '小程序',
            4 => '无跳转',
        ];
        return $map[$this->link_type] ?? '未知';
    }

    /**
     * 获取投放平台文本
     * @return string
     */
    public function getPlatformTextAttribute(): string
    {
        $map = [
            1 => '全部',
            2 => 'PC端',
            3 => '移动端',
            4 => '小程序',
        ];
        return $map[$this->platform] ?? '未知';
    }

    /**
     * 获取设备类型文本
     * @return string
     */
    public function getDeviceTypeTextAttribute(): string
    {
        $map = [
            1 => '全部',
            2 => 'iOS',
            3 => 'Android',
            4 => '其他',
        ];
        return $map[$this->device_type] ?? '未知';
    }

    /**
     * 获取用户定向文本
     * @return string
     */
    public function getTargetUserTypeTextAttribute(): string
    {
        $map = [
            0 => '全部用户',
            1 => '新用户',
            2 => '老用户',
            3 => 'VIP用户',
            4 => '指定用户组',
        ];
        return $map[$this->target_user_type] ?? '未知';
    }

    /**
     * 获取展示时间类型文本
     * @return string
     */
    public function getShowTimeTypeTextAttribute(): string
    {
        return $this->show_time_type == 0 ? '全天' : '自定义时间段';
    }

    /**
     * 获取展示频率文本
     * @return string
     */
    public function getDisplayFrequencyTextAttribute(): string
    {
        $map = [
            1 => '每人每天1次',
            2 => '每人每小时1次',
            3 => '无限次',
        ];
        return $map[$this->display_frequency] ?? '未知';
    }

    /**
     * 获取计费方式文本
     * @return string
     */
    public function getCostTypeTextAttribute(): string
    {
        $map = [
            1 => 'CPM',
            2 => 'CPC',
            3 => 'CPT',
            4 => 'CPA',
        ];
        return $map[$this->cost_type] ?? '未知';
    }

    /**
     * 获取状态文本
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        $map = [
            1 => '草稿',
            2 => '待审核',
            3 => '审核通过',
            4 => '投放中',
            5 => '已结束',
            6 => '已暂停',
            7 => '审核驳回',
            8 => '已下线',
        ];
        return $map[$this->status] ?? '未知';
    }

    /**
     * 获取审核状态文本
     * @return string
     */
    public function getAuditStatusTextAttribute(): string
    {
        $map = [
            0 => '未提交',
            1 => '待审核',
            2 => '审核通过',
            3 => '审核驳回',
        ];
        return $map[$this->audit_status] ?? '未知';
    }

    /**
     * 获取状态标签样式
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        $map = [
            1 => 'layui-btn layui-btn-xs layui-btn-primary',
            2 => 'layui-btn layui-btn-xs layui-btn-warm',
            3 => 'layui-btn layui-btn-xs',
            4 => 'layui-btn layui-btn-xs layui-btn-normal',
            5 => 'layui-btn layui-btn-xs',
            6 => 'layui-btn layui-btn-xs layui-btn-danger',
            7 => 'layui-btn layui-btn-xs layui-btn-danger',
            8 => 'layui-btn layui-btn-xs',
        ];
        return $map[$this->status] ?? 'layui-btn layui-btn-xs';
    }
}