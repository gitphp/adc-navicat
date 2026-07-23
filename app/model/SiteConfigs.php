<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 站点配置模型
 * @property int $id 主键ID
 * @property string $conf_group 配置分组
 * @property string $conf_key 配置键名
 * @property string $conf_value 配置值
 * @property string $conf_desc 配置说明
 * @property string $input_type 输入类型
 * @property int $conf_sort 排序
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class SiteConfigs extends Model
{
    // 设置表名
    protected $name = 'site_configs';

    // 设置主键
    protected $pk = 'id';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 获取分组名称
     * @return string
     */
    public function getConfGroupTextAttribute(): string
    {
        $groups = [
            'basic' => '基础设置',
            'seo' => 'SEO优化',
            'contact' => '联系方式',
            'social' => '社交账号',
        ];
        return $groups[$this->conf_group] ?? $this->conf_group;
    }

    /**
     * 获取输入类型名称
     * @return string
     */
    public function getInputTypeTextAttribute(): string
    {
        $types = [
            'text' => '文本输入',
            'textarea' => '文本域',
            'image' => '图片上传',
            'file' => '文件上传',
            'json' => 'JSON格式',
        ];
        return $types[$this->input_type] ?? $this->input_type;
    }

    /**
     * 根据分组获取配置列表
     * @param string $group
     * @return array
     */
    public static function getConfigsByGroup(string $group): array
    {
        return self::where('conf_group', $group)
            ->order('conf_sort', 'asc')
            ->order('id', 'asc')
            ->column('conf_value', 'conf_key');
    }

    /**
     * 获取所有配置（按分组）
     * @return array
     */
    public static function getAllConfigs(): array
    {
        $configs = self::order('conf_sort', 'asc')->order('id', 'asc')->select();
        $result = [];
        foreach ($configs as $config) {
            $result[$config['conf_group']][$config['conf_key']] = $config['conf_value'];
        }
        return $result;
    }

    /**
     * 获取配置值
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public static function getConfig(string $key, string $default = null)
    {
        $config = self::where('conf_key', $key)->find();
        return $config ? $config->conf_value : $default;
    }

    /**
     * 设置配置值
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function setConfig(string $key, string $value): bool
    {
        $config = self::where('conf_key', $key)->find();
        if ($config) {
            $config->conf_value = $value;
            return $config->save();
        }
        return false;
    }
}