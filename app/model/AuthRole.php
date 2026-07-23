<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 角色信息模型
 * @property int $id 角色ID
 * @property string $role_name 角色名称
 * @property string $role_code 角色唯一标识
 * @property int $role_type 角色类型
 * @property int $role_sort 排序号
 * @property int $data_scope 数据权限范围
 * @property array $scope_departments 指定部门IDs
 * @property int $role_status 角色状态
 * @property string $role_remark 角色备注
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AuthRole extends Model
{
    use SoftDelete;
    
    // 设置表名
    protected $name = 'auth_role';
    
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
    protected $json = ['scope_departments'];
    protected $jsonAssoc = true;
    
    // 数据权限范围映射
    const DATA_SCOPE_ALL = 1;         // 全部数据
    const DATA_SCOPE_DEPARTMENT_AND_SUB = 2; // 本部门及下级
    const DATA_SCOPE_DEPARTMENT = 3;  // 本部门
    const DATA_SCOPE_SELF = 4;        // 仅本人数据
    const DATA_SCOPE_CUSTOM = 5;      // 自定义指定部门
    
    // 角色类型映射
    const ROLE_TYPE_SYSTEM = 1;       // 系统内置
    const ROLE_TYPE_CUSTOM = 2;       // 用户自定义
    
    // 角色状态映射
    const ROLE_STATUS_DISABLED = 0;   // 禁用
    const ROLE_STATUS_ENABLED = 1;    // 启用
    
    /**
     * 获取数据权限范围选项
     * @return array
     */
    public static function getDataScopeOptions(): array
    {
        return [
            self::DATA_SCOPE_ALL => '全部数据',
            self::DATA_SCOPE_DEPARTMENT_AND_SUB => '本部门及下级',
            self::DATA_SCOPE_DEPARTMENT => '本部门',
            self::DATA_SCOPE_SELF => '仅本人数据',
            self::DATA_SCOPE_CUSTOM => '自定义指定部门',
        ];
    }
    
    /**
     * 获取角色类型选项
     * @return array
     */
    public static function getRoleTypeOptions(): array
    {
        return [
            self::ROLE_TYPE_SYSTEM => '系统内置',
            self::ROLE_TYPE_CUSTOM => '用户自定义',
        ];
    }
    
    /**
     * 获取角色状态选项
     * @return array
     */
    public static function getRoleStatusOptions(): array
    {
        return [
            self::ROLE_STATUS_DISABLED => '禁用',
            self::ROLE_STATUS_ENABLED => '启用',
        ];
    }
    
    /**
     * 获取状态文本
     * @return string
     */
    public function getStatusText(): string
    {
        $options = self::getRoleStatusOptions();
        return $options[$this->role_status] ?? '未知';
    }
    
    /**
     * 获取类型文本
     * @return string
     */
    public function getTypeText(): string
    {
        $options = self::getRoleTypeOptions();
        return $options[$this->role_type] ?? '未知';
    }
    
    /**
     * 获取数据范围文本
     * @return string
     */
    public function getDataScopeText(): string
    {
        $options = self::getDataScopeOptions();
        return $options[$this->data_scope] ?? '未知';
    }
}
