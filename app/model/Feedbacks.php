<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 用户留言模型
 * @property int $id 主键ID
 * @property string $fb_name 联系人姓名
 * @property string $fb_phone 联系电话
 * @property string $fb_email 邮箱
 * @property string $fb_company 公司名称
 * @property string $fb_title 留言标题
 * @property string $fb_content 留言内容
 * @property int $fb_status 状态
 * @property string $reply_content 回复内容
 * @property string $replied_at 回复时间
 * @property string $ip IP地址
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Feedbacks extends Model
{
    // 设置表名
    protected $name = 'feedbacks';

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
    public function getFbStatusTextAttribute(): string
    {
        return $this->fb_status == 1 ? '已处理' : '未处理';
    }

    /**
     * 获取状态标签样式
     * @return string
     */
    public function getFbStatusClassAttribute(): string
    {
        return $this->fb_status == 1 ? 'layui-btn layui-btn-xs layui-btn-normal' : 'layui-btn layui-btn-xs layui-btn-warm';
    }
}