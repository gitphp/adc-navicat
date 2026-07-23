<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\AdPositions;
use app\model\AdSlots;
use app\validate\AdPositionsValidate;

/**
 * 后台广告位管理控制器（广告位主表）
 */
class BackendAdPositions extends BackendBase
{
    /**
     * 广告列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '广告管理';
        return $this->render('adpositions/index');
    }

    /**
     * 获取广告列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $ad_title = $this->request->param('ad_title', '');
        $position_code = $this->request->param('position_code', '');
        $status = $this->request->param('status', '', 'intval');
        $platform = $this->request->param('platform', '', 'intval');
        
        // 构建查询
        $query = AdPositions::order('sort', 'desc')->order('created_at', 'desc');
        
        // 搜索条件
        if (!empty($ad_title)) {
            $query->where('ad_title', 'like', '%' . $ad_title . '%');
        }
        if (!empty($position_code)) {
            $query->where('position_code', 'like', '%' . $position_code . '%');
        }
        if ($status !== '') {
            $query->where('status', $status);
        }
        if ($platform !== '') {
            $query->where('platform', $platform);
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
     * 添加广告页面
     * @return \think\Response
     */
    public function add()
    {
        // 获取广告位列表
        $adSlots = AdSlots::where('slot_status', 1)->select();
        return view('adpositions/add', [
            'adSlots' => $adSlots,
        ]);
    }

    /**
     * 保存广告
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new AdPositionsValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 创建广告
        $adPosition = new AdPositions();
        $adPosition->ad_title = $data['ad_title'];
        $adPosition->subtitle = $data['subtitle'] ?? '';
        $adPosition->cover_url = $data['cover_url'] ?? '';
        $adPosition->cover_mobile = $data['cover_mobile'] ?? '';
        $adPosition->cover_thumb = $data['cover_thumb'] ?? '';
        $adPosition->video_url = $data['video_url'] ?? '';
        $adPosition->link_type = $data['link_type'] ?? 1;
        $adPosition->link_url = $data['link_url'] ?? '';
        $adPosition->app_id = $data['app_id'] ?? '';
        $adPosition->app_path = $data['app_path'] ?? '';
        $adPosition->position_code = $data['position_code'] ?? '';
        $adPosition->platform = $data['platform'] ?? 1;
        $adPosition->device_type = $data['device_type'] ?? 1;
        $adPosition->target_user_type = $data['target_user_type'] ?? 0;
        $adPosition->start_time = $data['start_time'];
        $adPosition->end_time = $data['end_time'];
        $adPosition->show_time_type = $data['show_time_type'] ?? 0;
        $adPosition->sort = $data['sort'] ?? 0;
        $adPosition->display_frequency = $data['display_frequency'] ?? 1;
        $adPosition->daily_impression_limit = $data['daily_impression_limit'] ?? 0;
        $adPosition->daily_click_limit = $data['daily_click_limit'] ?? 0;
        $adPosition->cost_type = $data['cost_type'] ?? 1;
        $adPosition->budget = $data['budget'] ?? null;
        $adPosition->bid_price = $data['bid_price'] ?? null;
        $adPosition->status = 1; // 默认草稿
        $adPosition->audit_status = 0;
        $adPosition->created_by = $this->request->session('user_id', 0);
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '添加成功',
                'data' => $adPosition,
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
     * 编辑广告页面
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return '广告不存在';
        }
        
        // 获取广告位列表
        $adSlots = AdSlots::where('slot_status', 1)->select();
        
        return view('adpositions/edit', [
            'adPosition' => $adPosition,
            'adSlots' => $adSlots,
        ]);
    }

    /**
     * 更新广告
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new AdPositionsValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $adPosition->ad_title = $data['ad_title'];
        $adPosition->subtitle = $data['subtitle'] ?? '';
        $adPosition->cover_url = $data['cover_url'] ?? '';
        $adPosition->cover_mobile = $data['cover_mobile'] ?? '';
        $adPosition->cover_thumb = $data['cover_thumb'] ?? '';
        $adPosition->video_url = $data['video_url'] ?? '';
        $adPosition->link_type = $data['link_type'] ?? 1;
        $adPosition->link_url = $data['link_url'] ?? '';
        $adPosition->app_id = $data['app_id'] ?? '';
        $adPosition->app_path = $data['app_path'] ?? '';
        $adPosition->position_code = $data['position_code'] ?? '';
        $adPosition->platform = $data['platform'] ?? 1;
        $adPosition->device_type = $data['device_type'] ?? 1;
        $adPosition->target_user_type = $data['target_user_type'] ?? 0;
        $adPosition->start_time = $data['start_time'];
        $adPosition->end_time = $data['end_time'];
        $adPosition->show_time_type = $data['show_time_type'] ?? 0;
        $adPosition->sort = $data['sort'] ?? 0;
        $adPosition->display_frequency = $data['display_frequency'] ?? 1;
        $adPosition->daily_impression_limit = $data['daily_impression_limit'] ?? 0;
        $adPosition->daily_click_limit = $data['daily_click_limit'] ?? 0;
        $adPosition->cost_type = $data['cost_type'] ?? 1;
        $adPosition->budget = $data['budget'] ?? null;
        $adPosition->bid_price = $data['bid_price'] ?? null;
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '更新成功',
                'data' => $adPosition,
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
     * 删除广告
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        if ($adPosition->delete()) {
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
     * 提交审核
     * @return \think\response\Json
     */
    public function submitAudit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 2; // 待审核
        $adPosition->audit_status = 1;
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '已提交审核',
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

    /**
     * 审核通过
     * @return \think\response\Json
     */
    public function auditPass()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 3; // 审核通过
        $adPosition->audit_status = 2;
        $adPosition->reviewer_id = $this->request->session('user_id', 0);
        $adPosition->reviewed_at = date('Y-m-d H:i:s');
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '审核通过',
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

    /**
     * 审核驳回
     * @return \think\response\Json
     */
    public function auditReject()
    {
        $id = $this->request->param('id', 0, 'intval');
        $reject_reason = $this->request->param('reject_reason', '');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        if (empty($reject_reason)) {
            return json([
                'code' => 0,
                'msg' => '请填写驳回原因',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 7; // 审核驳回
        $adPosition->audit_status = 3;
        $adPosition->reviewer_id = $this->request->session('user_id', 0);
        $adPosition->reviewed_at = date('Y-m-d H:i:s');
        $adPosition->reject_reason = $reject_reason;
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '已驳回',
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

    /**
     * 开始投放
     * @return \think\response\Json
     */
    public function start()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        if ($adPosition->audit_status != 2) {
            return json([
                'code' => 0,
                'msg' => '请先通过审核',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 4; // 投放中
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '已开始投放',
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

    /**
     * 暂停投放
     * @return \think\response\Json
     */
    public function pause()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 6; // 已暂停
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '已暂停投放',
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

    /**
     * 下线广告
     * @return \think\response\Json
     */
    public function offline()
    {
        $id = $this->request->param('id', 0, 'intval');
        $adPosition = AdPositions::find($id);
        
        if (!$adPosition) {
            return json([
                'code' => 0,
                'msg' => '广告不存在',
                'data' => null,
            ]);
        }
        
        $adPosition->status = 8; // 已下线
        
        if ($adPosition->save()) {
            return json([
                'code' => 1,
                'msg' => '已下线',
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