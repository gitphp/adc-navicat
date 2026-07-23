<?php
declare (strict_types = 1);

namespace app\middleware;

use app\model\OperationLog;
use think\facade\Session;
use think\Request;
use think\Response;

/**
 * 操作日志中间件
 */
class OperationLogMiddleware
{
    /**
     * 不需要记录日志的路径
     * @var array
     */
    protected $excludePaths = [
        '/backend/login/checkSession',
        '/backend/login/check',
        '/backend/login/captcha',
        '/backend/operationlog/list',
        '/backend/operationlog/actions',
        '/backend/operationlog/bizTypes',
    ];

    /**
     * 处理请求
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next): Response
    {
        // 排除不需要记录日志的路径
        $url = $request->url();
        foreach ($this->excludePaths as $excludePath) {
            if (strpos($url, $excludePath) === 0) {
                return $next($request);
            }
        }

        // 获取请求信息
        $method = $request->method();
        $controller = $request->controller();
        $action = $request->action();
        $ip = $request->ip();
        $userAgent = $request->header('user-agent', '');
        
        // 获取用户信息
        $session = Session::all();
        $operatorId = $session['user_id'] ?? 0;
        $operatorName = $session['user_nick'] ?? '匿名用户';
        
        // 获取请求参数
        $params = $request->param();
        // 移除敏感信息
        unset($params['password'], $params['captcha']);
        
        // 解析业务类型和操作类型
        list($bizType, $actionType) = $this->parseBizType($controller, $action);
        
        // 执行请求
        $response = $next($request);
        
        // 记录日志
        $this->log($operatorId, $operatorName, $bizType, $actionType, $method, $url, $controller . '::' . $action, $ip, $userAgent, $params, $response);
        
        return $response;
    }

    /**
     * 解析业务类型和操作类型
     * @param string $controller
     * @param string $action
     * @return array
     */
    protected function parseBizType(string $controller, string $action): array
    {
        // 移除前缀
        $controller = str_replace('Backend', '', $controller);
        $controller = strtolower($controller);
        
        // 映射操作类型
        $actionMap = [
            'save' => 'INSERT',
            'update' => 'UPDATE',
            'del' => 'DELETE',
            'add' => 'VIEW',
            'edit' => 'VIEW',
            'index' => 'VIEW',
            'list' => 'VIEW',
            'status' => 'UPDATE',
            'handle' => 'UPDATE',
            'reply' => 'UPDATE',
            'login' => 'LOGIN',
            'logout' => 'LOGIN',
        ];
        
        $actionType = $actionMap[$action] ?? 'VIEW';
        $bizType = $controller;
        
        return [$bizType, $actionType];
    }

    /**
     * 记录日志
     * @param int $operatorId
     * @param string $operatorName
     * @param string $bizType
     * @param string $actionType
     * @param string $method
     * @param string $url
     * @param string $methodFun
     * @param string $ip
     * @param string $userAgent
     * @param array $params
     * @param Response $response
     */
    protected function log(string $operatorId, string $operatorName, string $bizType, string $actionType, string $method, string $url, string $methodFun, string $ip, string $userAgent, array $params, Response $response): void
    {
        try {
            // 获取响应内容
            $responseContent = $response->getContent();
            
            // 解析响应结果
            $status = 1;
            $errorMsg = '';
            $bizId = 0;
            $bizLabel = '';
            
            if (!empty($responseContent)) {
                $result = json_decode($responseContent, true);
                if (is_array($result)) {
                    $status = $result['code'] ?? 1;
                    $errorMsg = $result['msg'] ?? '';
                    
                    // 获取业务ID和标签
                    if (isset($result['data']['id'])) {
                        $bizId = (string)($result['data']['id']);
                    } elseif (isset($params['id'])) {
                        $bizId = (string)($params['id']);
                    }
                    
                    // 获取业务标签
                    if (isset($result['data']['name'])) {
                        $bizLabel = $result['data']['name'];
                    } elseif (isset($params['name'])) {
                        $bizLabel = $params['name'];
                    }
                }
            }
            
            // 构建日志数据
            $logData = [
                'operator_id' => $operatorId,
                'operator_name' => $operatorName,
                'biz_type' => $bizType,
                'activity_type' => $bizType . '_' . strtolower($actionType),
                'action' => $actionType,
                'biz_id' => $bizId,
                'biz_label' => $bizLabel,
                'new_value' => $params,
                'operator_status' => $status,
                'error_msg' => $errorMsg,
                'client_ip' => $ip,
                'user_agent' => $userAgent,
                'request_url' => $url,
                'method_fun' => $methodFun,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            // 异步记录日志（不阻塞请求）
            OperationLog::record($logData);
        } catch (\Exception $e) {
            // 日志记录失败不影响主业务
            // 可以在这里记录到错误日志
        }
    }
}