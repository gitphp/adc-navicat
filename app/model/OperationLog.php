<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 用户操作日志模型
 * @property int $id 主键(雪花ID)
 * @property int $operator_id 操作人ID
 * @property string $operator_name 操作人名称
 * @property string $biz_type 业务模块类型
 * @property string $activity_type 活动类型
 * @property string $action 操作类型
 * @property int $biz_id 目标实体ID
 * @property string $biz_label 高亮展示文本
 * @property string $old_value 修改前的数据快照
 * @property string $new_value 修改后的数据快照
 * @property int $operator_status 操作状态
 * @property string $error_msg 错误信息
 * @property string $client_ip 客户端IP
 * @property string $user_agent 用户浏览器/设备信息
 * @property string $request_url 触发日志的API URL
 * @property string $method_fun 触发日志的方法名
 * @property string $created_at 发生时间
 */
class OperationLog extends Model
{
    // 设置表名
    protected $name = 'operation_log';

    // 设置主键
    protected $pk = 'id';

    // 自动写入时间戳
    protected $autoWriteTimestamp = false;

    // JSON字段
    protected $json = ['old_value', 'new_value'];
    protected $jsonAssoc = true;

    /**
     * 获取操作状态文本
     * @return string
     */
    public function getOperatorStatusTextAttribute(): string
    {
        return $this->operator_status == 1 ? '成功' : '失败';
    }

    /**
     * 获取操作类型文本
     * @return string
     */
    public function getActionTextAttribute(): string
    {
        $actions = [
            'INSERT' => '新增',
            'UPDATE' => '修改',
            'DELETE' => '删除',
            'LOGIN' => '登录',
            'VIEW' => '查看',
            'EXPORT' => '导出',
            'IMPORT' => '导入',
        ];
        return $actions[$this->action] ?? $this->action;
    }

    /**
     * 获取业务模块文本
     * @return string
     */
    public function getBizTypeTextAttribute(): string
    {
        $types = [
            'user' => '用户管理',
            'role' => '角色管理',
            'permission' => '权限管理',
            'menu' => '菜单管理',
            'article' => '文章管理',
            'category' => '分类管理',
            'bookmark' => '书签管理',
            'adslots' => '广告位管理',
            'adpositions' => '广告管理',
            'friendlinks' => '友情链接',
            'feedbacks' => '用户留言',
            'siteconfigs' => '站点配置',
            'system' => '系统操作',
        ];
        return $types[$this->biz_type] ?? $this->biz_type;
    }

    /**
     * 记录操作日志
     * @param array $data
     * @return bool
     */
    public static function record(array $data): bool
    {
        $log = new self();
        $log->operator_id = $data['operator_id'] ?? 0;
        $log->operator_name = $data['operator_name'] ?? '';
        $log->biz_type = $data['biz_type'] ?? '';
        $log->activity_type = $data['activity_type'] ?? '';
        $log->action = $data['action'] ?? '';
        $log->biz_id = $data['biz_id'] ?? 0;
        $log->biz_label = $data['biz_label'] ?? '';
        // 由于模型已配置json字段自动序列化，直接传入数组即可
        $log->old_value = $data['old_value'] ?? null;
        $log->new_value = $data['new_value'] ?? null;
        $log->operator_status = $data['operator_status'] ?? 1;
        $log->error_msg = $data['error_msg'] ?? '';
        $log->client_ip = $data['client_ip'] ?? '';
        $log->user_agent = $data['user_agent'] ?? '';
        $log->request_url = $data['request_url'] ?? '';
        $log->method_fun = $data['method_fun'] ?? '';
        $log->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        
        return $log->save();
    }

    /**
     * 记录登录日志
     * @param int $operatorId
     * @param string $operatorName
     * @param string $ip
     * @param string $userAgent
     * @param int $status
     * @param string $errorMsg
     * @return bool
     */
    public static function recordLogin(int $operatorId, string $operatorName, string $ip, string $userAgent, int $status = 1, string $errorMsg = ''): bool
    {
        return self::record([
            'operator_id' => $operatorId,
            'operator_name' => $operatorName,
            'biz_type' => 'system',
            'activity_type' => 'user_login',
            'action' => 'LOGIN',
            'operator_status' => $status,
            'error_msg' => $errorMsg,
            'client_ip' => $ip,
            'user_agent' => $userAgent,
            'request_url' => '/backend/login/doLogin',
            'method_fun' => 'BackendLogin::doLogin',
        ]);
    }

    /**
     * 记录CRUD操作
     * @param string $bizType
     * @param string $action
     * @param int $bizId
     * @param string $bizLabel
     * @param array|null $oldValue
     * @param array|null $newValue
     * @param int $status
     * @param string $errorMsg
     * @return bool
     */
    public static function recordCrud(string $bizType, string $action, int $bizId = 0, string $bizLabel = '', array $oldValue = null, array $newValue = null, int $status = 1, string $errorMsg = ''): bool
    {
        $session = session();
        $operatorId = $session['user_id'] ?? 0;
        $operatorName = $session['user_nick'] ?? '';
        
        $request = request();
        
        return self::record([
            'operator_id' => $operatorId,
            'operator_name' => $operatorName,
            'biz_type' => $bizType,
            'activity_type' => $bizType . '_' . strtolower($action),
            'action' => $action,
            'biz_id' => $bizId,
            'biz_label' => $bizLabel,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'operator_status' => $status,
            'error_msg' => $errorMsg,
            'client_ip' => $request->ip(),
            'user_agent' => $request->header('user-agent', ''),
            'request_url' => $request->url(),
            'method_fun' => $request->controller() . '::' . $request->action(),
        ]);
    }
}