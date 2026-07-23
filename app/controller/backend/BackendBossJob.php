<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\BossJob;
use app\validate\BossJobValidate;

/**
 * 后台招聘职位管理控制器
 */
class BackendBossJob extends BackendBase
{
    /**
     * 招聘职位列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '招聘职位管理';
        return $this->render('bossjob/index');
    }

    /**
     * 获取招聘职位列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取搜索条件
        $jobTitle = $this->request->get('job_title', '');
        $department = $this->request->get('department', '');
        $jobStatus = $this->request->get('job_status', '');
        $isHot = $this->request->get('is_hot', '');
        
        // 构建查询
        $query = BossJob::whereNull('deleted_at')->order('job_sort', 'asc');
        
        // 搜索条件
        if ($jobTitle) {
            $query->where('job_title', 'like', '%' . $jobTitle . '%');
        }
        if ($department) {
            $query->where('department', 'like', '%' . $department . '%');
        }
        if ($jobStatus !== '') {
            $query->where('job_status', $jobStatus);
        }
        if ($isHot !== '') {
            $query->where('is_hot', $isHot);
        }
        
        // 分页
        $list = $query->paginate($this->request->get('limit', 20));
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $job) {
            $data[] = [
                'id'            => $job->id,
                'job_title'     => $job->job_title,
                'department'    => $job->department,
                'workplace'     => $job->workplace,
                'experience'    => $job->experience,
                'education'     => $job->education,
                'salary_range'  => $job->salary_range,
                'is_hot'        => $job->is_hot,
                'is_hot_text'   => BossJob::getHotText($job->is_hot),
                'is_hot_class'  => BossJob::getHotClass($job->is_hot),
                'job_status'    => $job->job_status,
                'job_status_text' => BossJob::getStatusText($job->job_status),
                'job_status_class' => BossJob::getStatusClass($job->job_status),
                'expire_at'     => $job->expire_at,
                'view_count'    => $job->view_count,
                'job_sort'      => $job->job_sort,
                'created_at'    => $job->created_at,
            ];
        }
        
        return json([
            'code'  => 0,
            'msg'   => 'success',
            'count' => $list->total(),
            'data'  => $data,
        ]);
    }

    /**
     * 添加招聘职位页面
     * @return \think\Response
     */
    public function add()
    {
        return view('bossjob/add');
    }

    /**
     * 保存招聘职位
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        $validate = new BossJobValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $job = new BossJob();
        $job->job_title     = $data['job_title'];
        $job->department    = $data['department'] ?? '';
        $job->workplace     = $data['workplace'] ?? '';
        $job->experience    = $data['experience'] ?? '';
        $job->education     = $data['education'] ?? '';
        $job->salary_range  = $data['salary_range'] ?? '';
        $job->description   = $data['description'] ?? '';
        $job->requirements  = $data['requirements'] ?? '';
        $job->benefits      = $data['benefits'] ?? '';
        $job->is_hot        = $data['is_hot'] ?? 0;
        $job->job_status    = $data['job_status'] ?? 1;
        $job->expire_at     = $data['expire_at'] ?? null;
        $job->job_sort      = $data['job_sort'] ?? 0;
        
        $result = $job->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '添加成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '添加失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 编辑招聘职位页面
     * @param string $id 职位ID
     * @return \think\Response
     */
    public function edit(string $id)
    {
        $job = BossJob::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        return view('bossjob/edit', [
            'job' => $job,
        ]);
    }

    /**
     * 更新招聘职位
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        
        if (!$data['id']) {
            return json([
                'code' => 0,
                'msg'  => '缺少职位ID',
                'data' => null,
            ]);
        }
        
        // 验证数据
        $validate = new BossJobValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $job = BossJob::where('id', $data['id'])->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        $job->job_title     = $data['job_title'];
        $job->department    = $data['department'] ?? '';
        $job->workplace     = $data['workplace'] ?? '';
        $job->experience    = $data['experience'] ?? '';
        $job->education     = $data['education'] ?? '';
        $job->salary_range  = $data['salary_range'] ?? '';
        $job->description   = $data['description'] ?? '';
        $job->requirements  = $data['requirements'] ?? '';
        $job->benefits      = $data['benefits'] ?? '';
        $job->is_hot        = $data['is_hot'] ?? 0;
        $job->job_status    = $data['job_status'] ?? 1;
        $job->expire_at     = $data['expire_at'] ?? null;
        $job->job_sort      = $data['job_sort'] ?? 0;
        
        $result = $job->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '更新成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '更新失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 删除招聘职位（软删除）
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少职位ID',
                'data' => null,
            ]);
        }
        
        $job = BossJob::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        // 软删除
        if ($job->delete()) {
            return json([
                'code' => 1,
                'msg'  => '删除成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '删除失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 发布职位
     * @return \think\response\Json
     */
    public function publish()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少职位ID',
                'data' => null,
            ]);
        }
        
        $job = BossJob::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        if ($job->job_status == 2) {
            return json([
                'code' => 0,
                'msg'  => '职位已发布',
                'data' => null,
            ]);
        }
        
        if (BossJob::publish((string)$id)) {
            return json([
                'code' => 1,
                'msg'  => '发布成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '发布失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 关闭职位
     * @return \think\response\Json
     */
    public function close()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少职位ID',
                'data' => null,
            ]);
        }
        
        $job = BossJob::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        if ($job->job_status == 3) {
            return json([
                'code' => 0,
                'msg'  => '职位已关闭',
                'data' => null,
            ]);
        }
        
        if (BossJob::close((string)$id)) {
            return json([
                'code' => 1,
                'msg'  => '关闭成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '关闭失败',
                'data' => null,
            ]);
        }
    }
    
    /**
     * 切换急聘状态
     * @return \think\response\Json
     */
    public function hot()
    {
        $id = $this->request->post('id', 0);
        $isHot = $this->request->post('is_hot', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少职位ID',
                'data' => null,
            ]);
        }
        
        $job = BossJob::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$job) {
            return json([
                'code' => 0,
                'msg'  => '职位不存在',
                'data' => null,
            ]);
        }
        
        $job->is_hot = $isHot;
        $result = $job->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '更新成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '更新失败',
                'data' => null,
            ]);
        }
    }
}