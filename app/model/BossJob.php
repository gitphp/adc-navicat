<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 招聘职位模型
 */
class BossJob extends Model
{
    /**
     * 设置表名
     * @var string
     */
    protected $name = 'boss_job';
    
    /**
     * 设置主键
     * @var string
     */
    protected $pk = 'id';
    
    /**
     * 软删除字段（datetime类型）
     * @var string
     */
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;
    
    /**
     * 自动写入时间戳
     * @var bool
     */
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    
    /**
     * 时间字段格式化
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * 获取状态文本
     * @param int $status
     * @return string
     */
    public static function getStatusText(int $status): string
    {
        $options = [
            1 => '待发布',
            2 => '发布中',
            3 => '已关闭',
        ];
        return $options[$status] ?? '未知';
    }
    
    /**
     * 获取状态样式
     * @param int $status
     * @return string
     */
    public static function getStatusClass(int $status): string
    {
        $options = [
            1 => 'layui-btn-warm',
            2 => 'layui-btn-normal',
            3 => 'layui-btn-danger',
        ];
        return $options[$status] ?? 'layui-btn-primary';
    }
    
    /**
     * 获取急聘文本
     * @param int $isHot
     * @return string
     */
    public static function getHotText(int $isHot): string
    {
        return $isHot == 1 ? '急聘' : '';
    }
    
    /**
     * 获取急聘样式
     * @param int $isHot
     * @return string
     */
    public static function getHotClass(int $isHot): string
    {
        return $isHot == 1 ? 'layui-badge-danger' : '';
    }
    
    /**
     * 获取状态文本（访问器）
     * @return string
     */
    public function getJobStatusTextAttribute(): string
    {
        return self::getStatusText($this->job_status);
    }
    
    /**
     * 获取状态样式类（访问器）
     * @return string
     */
    public function getJobStatusClassAttribute(): string
    {
        return self::getStatusClass($this->job_status);
    }
    
    /**
     * 获取急聘文本（访问器）
     * @return string
     */
    public function getIsHotTextAttribute(): string
    {
        return self::getHotText($this->is_hot);
    }
    
    /**
     * 浏览量+1
     * @param string $id
     * @return bool
     */
    public static function incrementView(string $id): bool
    {
        return self::where('id', $id)->whereNull('deleted_at')->inc('view_count')->update() !== false;
    }
    
    /**
     * 发布职位
     * @param string $id
     * @return bool
     */
    public static function publish(string $id): bool
    {
        return self::where('id', $id)->whereNull('deleted_at')->update(['job_status' => 2]) !== false;
    }
    
    /**
     * 关闭职位
     * @param string $id
     * @return bool
     */
    public static function close(string $id): bool
    {
        return self::where('id', $id)->whereNull('deleted_at')->update(['job_status' => 3]) !== false;
    }
}