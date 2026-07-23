<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\User;
use app\model\BookMark;
use app\model\Category;
use app\model\Feedbacks;
use app\model\BossJob;
use app\model\ConArticle;
use app\model\OperationLog;
use app\model\FriendLinks;
use app\model\AdPositions;
use app\model\AdSlots;

/**
 * 后台首页控制器
 */
class Index extends BackendBase
{
    /**
     * 后台首页
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '系统管理后台';
        
        // 获取统计数据
        $stats = $this->getStatistics();
        
        return $this->render('index/index', [
            'stats' => $stats,
        ]);
    }
    
    /**
     * 获取统计数据
     * @return array
     */
    protected function getStatistics(): array
    {
        return [
            'user_count' => User::whereNull('deleted_at')->count(),
            'bookmark_count' => BookMark::where('status', 1)->count(),
            'category_count' => Category::whereNull('deleted_at')->count(),
            'feedback_count' => Feedbacks::where('fb_status', 0)->count(),
            'job_count' => BossJob::where('job_status', 1)->count(),
            'article_count' => ConArticle::where('art_status', 1)->count(),
            'today_user_count' => User::whereNull('deleted_at')->where('created_at', '>=', date('Y-m-d 00:00:00'))->count(),
            'today_article_count' => ConArticle::where('art_status', 1)->where('created_at', '>=', date('Y-m-d 00:00:00'))->count(),
        ];
    }
    
    /**
     * 获取统计数据接口（AJAX）
     * @return \think\response\Json
     */
    public function stats()
    {
        $stats = $this->getStatistics();
        
        return json([
            'code' => 0,
            'msg' => 'success',
            'data' => $stats,
        ]);
    }
    
    /**
     * 获取最近操作日志
     * @return \think\response\Json
     */
    public function logs()
    {
        $logs = OperationLog::order('created_at', 'desc')->limit(10)->select()->toArray();
        
        return json([
            'code' => 0,
            'msg' => 'success',
            'data' => $logs,
        ]);
    }
    
    /**
     * 获取系统信息
     * @return \think\response\Json
     */
    public function sysinfo()
    {
        $sysInfo = [
            'php_version' => PHP_VERSION,
            'server_os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? '',
            'mysql_version' => $this->getMysqlVersion(),
            'upload_max_size' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
            'thinkphp_version' => \think\facade\App::version(),
        ];
        
        return json([
            'code' => 0,
            'msg' => 'success',
            'data' => $sysInfo,
        ]);
    }
    
    /**
     * 获取MySQL版本
     * @return string
     */
    protected function getMysqlVersion(): string
    {
        try {
            $connection = \think\facade\Db::connect();
            $result = $connection->query('SELECT VERSION() as version');
            return $result[0]['version'] ?? '未知';
        } catch (\Exception $e) {
            return '未知';
        }
    }
    
    /**
     * 提交反馈
     * @return \think\response\Json
     */
    public function feedback()
    {
        $content = $this->request->post('content', '');
        
        if (!$content) {
            return json([
                'code' => 0,
                'msg' => '请输入反馈内容',
            ]);
        }
        
        // 保存反馈（可以保存到数据库或记录日志）
        \think\facade\Log::info('用户反馈: ' . $content);
        
        return json([
            'code' => 1,
            'msg' => '反馈提交成功',
        ]);
    }
    
    /**
     * 测试方法
     * @param string $name
     * @return string
     */
    public function hello($name = 'World')
    {
        return 'Hello, ' . $name . '!';
    }
}