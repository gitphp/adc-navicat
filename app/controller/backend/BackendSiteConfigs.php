<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\SiteConfigs;
use app\validate\SiteConfigsValidate;

/**
 * 后台站点配置管理控制器
 */
class BackendSiteConfigs extends BackendBase
{
    /**
     * 配置分组列表
     * @var array
     */
    protected $groups = [
        'basic' => '基础设置',
        'seo' => 'SEO优化',
        'contact' => '联系方式',
        'social' => '社交账号',
    ];

    /**
     * 站点配置管理页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '站点配置管理';
        return $this->render('siteconfigs/index');
    }

    /**
     * 获取配置列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $conf_group = $this->request->param('conf_group', '');
        $conf_key = $this->request->param('conf_key', '');
        
        // 构建查询
        $query = SiteConfigs::order('conf_sort', 'asc')->order('id', 'asc');
        
        // 搜索条件
        if (!empty($conf_group)) {
            $query->where('conf_group', $conf_group);
        }
        if (!empty($conf_key)) {
            $query->where('conf_key', 'like', '%' . $conf_key . '%');
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
     * 获取配置分组列表
     * @return \think\response\Json
     */
    public function groups()
    {
        return json([
            'code' => 1,
            'msg' => 'success',
            'data' => $this->groups,
        ]);
    }

    /**
     * 添加配置页面
     * @return \think\Response
     */
    public function add()
    {
        return view('siteconfigs/add', [
            'groups' => $this->groups,
        ]);
    }

    /**
     * 保存配置
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new SiteConfigsValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 检查键名是否已存在
        $exists = SiteConfigs::where('conf_key', $data['conf_key'])->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg' => '配置键名已存在',
                'data' => null,
            ]);
        }
        
        // 创建配置
        $config = new SiteConfigs();
        $config->conf_group = $data['conf_group'];
        $config->conf_key = $data['conf_key'];
        $config->conf_value = $data['conf_value'] ?? '';
        $config->conf_desc = $data['conf_desc'] ?? '';
        $config->input_type = $data['input_type'] ?? 'text';
        $config->conf_sort = $data['conf_sort'] ?? 0;
        
        if ($config->save()) {
            return json([
                'code' => 1,
                'msg' => '添加成功',
                'data' => $config,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '添加失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 编辑配置页面
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $config = SiteConfigs::find($id);
        
        if (!$config) {
            return '配置不存在';
        }
        
        return view('siteconfigs/edit', [
            'config' => $config,
            'groups' => $this->groups,
        ]);
    }

    /**
     * 更新配置
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new SiteConfigsValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $config = SiteConfigs::find($id);
        
        if (!$config) {
            return json([
                'code' => 0,
                'msg' => '配置不存在',
                'data' => null,
            ]);
        }
        
        // 检查键名是否已存在（排除自身）
        $exists = SiteConfigs::where('conf_key', $data['conf_key'])
            ->where('id', '<>', $id)
            ->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg' => '配置键名已存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $config->conf_group = $data['conf_group'];
        $config->conf_key = $data['conf_key'];
        $config->conf_value = $data['conf_value'] ?? '';
        $config->conf_desc = $data['conf_desc'] ?? '';
        $config->input_type = $data['input_type'] ?? 'text';
        $config->conf_sort = $data['conf_sort'] ?? 0;
        
        if ($config->save()) {
            return json([
                'code' => 1,
                'msg' => '更新成功',
                'data' => $config,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '更新失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 删除配置
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $config = SiteConfigs::find($id);
        
        if (!$config) {
            return json([
                'code' => 0,
                'msg' => '配置不存在',
                'data' => null,
            ]);
        }
        
        if ($config->delete()) {
            return json([
                'code' => 1,
                'msg' => '删除成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '删除失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 批量保存配置（按分组）
     * @return \think\response\Json
     */
    public function saveBatch()
    {
        $data = $this->request->param();
        
        if (!isset($data['configs']) || !is_array($data['configs'])) {
            return json([
                'code' => 0,
                'msg' => '配置数据不能为空',
                'data' => null,
            ]);
        }
        
        foreach ($data['configs'] as $key => $value) {
            SiteConfigs::setConfig($key, $value);
        }
        
        return json([
            'code' => 1,
            'msg' => '保存成功',
            'data' => null,
        ]);
    }

    /**
     * 获取分组配置（用于表单展示）
     * @return \think\response\Json
     */
    public function groupConfig()
    {
        $group = $this->request->param('group', 'basic');
        
        $configs = SiteConfigs::where('conf_group', $group)
            ->order('conf_sort', 'asc')
            ->order('id', 'asc')
            ->select();
        
        return json([
            'code' => 1,
            'msg' => 'success',
            'data' => $configs,
        ]);
    }
}