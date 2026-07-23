<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\OperationLog;

/**
 * 后台操作日志管理控制器
 */
class BackendOperationLog extends BackendBase
{
    /**
     * 操作日志列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '操作日志管理';
        return $this->render('operationlog/index');
    }

    /**
     * 获取操作日志列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $operator_name = $this->request->param('operator_name', '');
        $biz_type = $this->request->param('biz_type', '');
        $action = $this->request->param('action', '');
        $operator_status = $this->request->param('operator_status', '', 'intval');
        $start_time = $this->request->param('start_time', '');
        $end_time = $this->request->param('end_time', '');
        
        // 构建查询
        $query = OperationLog::order('created_at', 'desc');
        
        // 搜索条件
        if (!empty($operator_name)) {
            $query->where('operator_name', 'like', '%' . $operator_name . '%');
        }
        if (!empty($biz_type)) {
            $query->where('biz_type', $biz_type);
        }
        if (!empty($action)) {
            $query->where('action', $action);
        }
        if ($operator_status !== '') {
            $query->where('operator_status', $operator_status);
        }
        if (!empty($start_time)) {
            $query->where('created_at', '>=', $start_time . ' 00:00:00');
        }
        if (!empty($end_time)) {
            $query->where('created_at', '<=', $end_time . ' 23:59:59');
        }
        
        // 分页查询
        $list = $query->paginate([
            'list_rows' => $limit,
            'page' => $page,
        ]);
        
        // 获取分页信息
        $total = $list->total();
        $data = $list->items();
        
        return json([
            'code' => 0,
            'msg' => 'success',
            'count' => $total,
            'data' => $data,
        ]);
    }

    /**
     * 查看日志详情
     * @return \think\Response
     */
    public function view()
    {
        $id = $this->request->param('id', 0, 'intval');
        $log = OperationLog::find($id);
        
        if (!$log) {
            return '日志不存在';
        }
        
        return view('operationlog/view', [
            'log' => $log,
        ]);
    }

    /**
     * 获取操作类型列表
     * @return \think\response\Json
     */
    public function actions()
    {
        $actions = [
            ['value' => 'INSERT', 'label' => '新增'],
            ['value' => 'UPDATE', 'label' => '修改'],
            ['value' => 'DELETE', 'label' => '删除'],
            ['value' => 'LOGIN', 'label' => '登录'],
            ['value' => 'VIEW', 'label' => '查看'],
            ['value' => 'EXPORT', 'label' => '导出'],
            ['value' => 'IMPORT', 'label' => '导入'],
        ];
        
        return json([
            'code' => 1,
            'msg' => 'success',
            'data' => $actions,
        ]);
    }

    /**
     * 获取业务模块列表
     * @return \think\response\Json
     */
    public function bizTypes()
    {
        $types = [
            ['value' => 'user', 'label' => '用户管理'],
            ['value' => 'role', 'label' => '角色管理'],
            ['value' => 'permission', 'label' => '权限管理'],
            ['value' => 'menu', 'label' => '菜单管理'],
            ['value' => 'article', 'label' => '文章管理'],
            ['value' => 'category', 'label' => '分类管理'],
            ['value' => 'bookmark', 'label' => '书签管理'],
            ['value' => 'adslots', 'label' => '广告位管理'],
            ['value' => 'adpositions', 'label' => '广告管理'],
            ['value' => 'friendlinks', 'label' => '友情链接'],
            ['value' => 'feedbacks', 'label' => '用户留言'],
            ['value' => 'siteconfigs', 'label' => '站点配置'],
            ['value' => 'system', 'label' => '系统操作'],
        ];
        
        return json([
            'code' => 1,
            'msg' => 'success',
            'data' => $types,
        ]);
    }
}