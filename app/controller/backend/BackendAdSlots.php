<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\AdSlots;
use app\validate\AdSlotsValidate;

/**
 * 后台广告位管理控制器
 */
class BackendAdSlots extends BackendBase
{
    /**
     * 广告位列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '广告位管理';
        return $this->render('adslots/index');
    }

    /**
     * 获取广告位列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $slot_code = $this->request->param('slot_code', '');
        $slot_name = $this->request->param('slot_name', '');
        $slot_status = $this->request->param('slot_status', '', 'intval');
        
        // 构建查询
        $query = AdSlots::order('id', 'asc');
        
        // 搜索条件
        if (!empty($slot_code)) {
            $query->where('slot_code', 'like', '%' . $slot_code . '%');
        }
        if (!empty($slot_name)) {
            $query->where('slot_name', 'like', '%' . $slot_name . '%');
        }
        if ($slot_status !== '') {
            $query->where('slot_status', $slot_status);
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
     * 添加广告位页面
     * @return \think\Response
     */
    public function add()
    {
        return view('adslots/add');
    }

    /**
     * 保存广告位
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new AdSlotsValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 检查编码是否已存在
        $exists = AdSlots::where('slot_code', $data['slot_code'])->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg' => '广告位编码已存在',
                'data' => null,
            ]);
        }
        
        // 创建广告位
        $adSlot = new AdSlots();
        $adSlot->slot_code = $data['slot_code'];
        $adSlot->slot_name = $data['slot_name'];
        $adSlot->description = $data['description'] ?? '';
        $adSlot->width = $data['width'] ?? 0;
        $adSlot->height = $data['height'] ?? 0;
        $adSlot->max_items = $data['max_items'] ?? 1;
        $adSlot->is_system = $data['is_system'] ?? 0;
        $adSlot->slot_status = $data['slot_status'] ?? 1;
        
        if ($adSlot->save()) {
            return json([
                'code' => 1,
                'msg' => '添加成功',
                'data' => $adSlot,
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
     * 编辑广告位页面
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adSlot = AdSlots::find($id);
        
        if (!$adSlot) {
            return '广告位不存在';
        }
        
        return view('adslots/edit', [
            'adSlot' => $adSlot,
        ]);
    }

    /**
     * 更新广告位
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new AdSlotsValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $adSlot = AdSlots::find($id);
        
        if (!$adSlot) {
            return json([
                'code' => 0,
                'msg' => '广告位不存在',
                'data' => null,
            ]);
        }
        
        // 检查编码是否被其他广告位占用
        $exists = AdSlots::where('slot_code', $data['slot_code'])
            ->where('id', '<>', $id)
            ->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg' => '广告位编码已存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $adSlot->slot_code = $data['slot_code'];
        $adSlot->slot_name = $data['slot_name'];
        $adSlot->description = $data['description'] ?? '';
        $adSlot->width = $data['width'] ?? 0;
        $adSlot->height = $data['height'] ?? 0;
        $adSlot->max_items = $data['max_items'] ?? 1;
        $adSlot->slot_status = $data['slot_status'] ?? 1;
        
        if ($adSlot->save()) {
            return json([
                'code' => 1,
                'msg' => '更新成功',
                'data' => $adSlot,
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
     * 删除广告位
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adSlot = AdSlots::find($id);
        
        if (!$adSlot) {
            return json([
                'code' => 0,
                'msg' => '广告位不存在',
                'data' => null,
            ]);
        }
        
        // 系统预设广告位不能删除
        if ($adSlot->is_system == 1) {
            return json([
                'code' => 0,
                'msg' => '系统预设广告位不能删除',
                'data' => null,
            ]);
        }
        
        if ($adSlot->delete()) {
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
     * 切换状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');
        
        $adSlot = AdSlots::find($id);
        
        if (!$adSlot) {
            return json([
                'code' => 0,
                'msg' => '广告位不存在',
                'data' => null,
            ]);
        }
        
        $adSlot->slot_status = $status;
        
        if ($adSlot->save()) {
            return json([
                'code' => 1,
                'msg' => $status == 1 ? '已启用' : '已禁用',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '操作失败',
                'data' => null,
            ]);
        }
    }
}