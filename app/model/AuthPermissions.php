<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 权限规则模型
 * @property int $id 主键ID
 * @property int $parent_id 父级权限ID
 * @property string $per_name 权限名称
 * @property string $per_code 权限唯一标识
 * @property string $per_type 权限类型
 * @property string $per_path 路由路径或API路径
 * @property string $per_method HTTP方法
 * @property string $per_icon 菜单图标
 * @property int $per_sort 排序权重
 * @property int $per_status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AuthPermissions extends Model
{
    use SoftDelete;
    
    // 设置表名
    protected $name = 'auth_permissions';
    
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
    
    // 权限类型映射
    const PER_TYPE_MENU = 'menu';    // 菜单
    const PER_TYPE_BUTTON = 'button'; // 按钮
    const PER_TYPE_API = 'api';      // 接口
    
    // 状态映射
    const PER_STATUS_DISABLED = 0;   // 禁用
    const PER_STATUS_ENABLED = 1;    // 启用
    
    /**
     * 获取权限类型选项
     * @return array
     */
    public static function getPerTypeOptions(): array
    {
        return [
            self::PER_TYPE_MENU => '菜单',
            self::PER_TYPE_BUTTON => '按钮',
            self::PER_TYPE_API => '接口',
        ];
    }
    
    /**
     * 获取状态选项
     * @return array
     */
    public static function getPerStatusOptions(): array
    {
        return [
            self::PER_STATUS_DISABLED => '禁用',
            self::PER_STATUS_ENABLED => '启用',
        ];
    }
    
    /**
     * 获取状态文本
     * @return string
     */
    public function getStatusText(): string
    {
        $options = self::getPerStatusOptions();
        return $options[$this->per_status] ?? '未知';
    }
    
    /**
     * 获取类型文本
     * @return string
     */
    public function getTypeText(): string
    {
        $options = self::getPerTypeOptions();
        return $options[$this->per_type] ?? '未知';
    }
    
    /**
     * 获取树形结构数据
     * @param int $parentId 父级ID
     * @return array
     */
    public static function getTree(int $parentId = 0): array
    {
        $list = self::where('parent_id', $parentId)
            ->where('per_status', self::PER_STATUS_ENABLED)
            ->order('per_sort', 'desc')
            ->order('id', 'asc')
            ->select()
            ->toArray();
        
        foreach ($list as &$item) {
            $children = self::getTree($item['id']);
            if (!empty($children)) {
                $item['children'] = $children;
            }
        }
        
        return $list;
    }
    
    /**
     * 获取所有权限列表（用于下拉选择）
     * @param int $excludeId 排除的ID
     * @param string $type 权限类型过滤
     * @return array
     */
    public static function getAllOptions(int $excludeId = 0, string $type = ''): array
    {
        $query = self::where('per_status', self::PER_STATUS_ENABLED);
        
        if ($excludeId > 0) {
            $query->where('id', '<>', $excludeId);
        }
        
        if ($type) {
            $query->where('per_type', $type);
        }
        
        $list = $query->order('per_sort', 'desc')->order('id', 'asc')->select();
        
        $options = [];
        foreach ($list as $item) {
            $options[] = [
                'id' => $item->id,
                'per_name' => $item->per_name,
                'per_code' => $item->per_code,
            ];
        }
        
        return $options;
    }
}
