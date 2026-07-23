<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\BookMark;
use app\model\Category;
use app\validate\BookMarkValidate;
use think\facade\Session;

/**
 * 后台书签管理控制器
 */
class BackendBookMark extends BackendBase
{
    /**
     * 书签列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '书签管理';
        return $this->render('bookmark/index');
    }

    /**
     * 获取书签列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $short_title = $this->request->param('short_title', '');
        $book_title = $this->request->param('book_title', '');
        $category_id = $this->request->param('category_id', 0, 'intval');
        $status = $this->request->param('status', '', 'intval');
        
        // 构建查询
        $query = BookMark::order('category_id', 'asc')
            ->order('sort_order', 'asc')
            ->order('id', 'asc');
        
        // 搜索条件
        if (!empty($short_title)) {
            $query->where('short_title', 'like', '%' . $short_title . '%');
        }
        if (!empty($book_title)) {
            $query->where('book_title', 'like', '%' . $book_title . '%');
        }
        if ($category_id > 0) {
            $query->where('category_id', $category_id);
        }
        if ($status !== '') {
            $query->where('status', $status);
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
     * 添加书签页面
     * @return \think\Response
     */
    public function add()
    {
        // 获取分类列表
        $categories = Category::where('cat_status', 1)->select()->toArray();
        
        return view('bookmark/add', [
            'categories' => $categories,
        ]);
    }

    /**
     * 保存书签
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new BookMarkValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 获取当前用户ID
        $userId = Session::get('user_id', 0);
        
        // 创建书签
        $bookmark = new BookMark();
        $bookmark->short_title = $data['short_title'];
        $bookmark->book_title = $data['book_title'];
        $bookmark->book_url = $data['book_url'];
        $bookmark->book_favicon = $data['book_favicon'] ?? '';
        $bookmark->book_desc = $data['book_desc'] ?? '';
        $bookmark->sort_order = $data['sort_order'] ?? 0;
        $bookmark->status = $data['status'] ?? 1;
        $bookmark->is_bold = $data['is_bold'] ?? 0;
        $bookmark->category_id = $data['category_id'] ?? 0;
        $bookmark->created_by = $userId;
        
        if ($bookmark->save()) {
            return json([
                'code' => 1,
                'msg' => '添加成功',
                'data' => $bookmark,
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
     * 编辑书签页面
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $bookmark = BookMark::find($id);
        
        if (!$bookmark) {
            return '书签不存在';
        }
        
        // 获取分类列表
        $categories = Category::where('cat_status', 1)->select()->toArray();
        
        return view('bookmark/edit', [
            'bookmark' => $bookmark,
            'categories' => $categories,
        ]);
    }

    /**
     * 更新书签
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new BookMarkValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $bookmark = BookMark::find($id);
        
        if (!$bookmark) {
            return json([
                'code' => 0,
                'msg' => '书签不存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $bookmark->short_title = $data['short_title'];
        $bookmark->book_title = $data['book_title'];
        $bookmark->book_url = $data['book_url'];
        $bookmark->book_favicon = $data['book_favicon'] ?? '';
        $bookmark->book_desc = $data['book_desc'] ?? '';
        $bookmark->sort_order = $data['sort_order'] ?? 0;
        $bookmark->status = $data['status'] ?? 1;
        $bookmark->is_bold = $data['is_bold'] ?? 0;
        $bookmark->category_id = $data['category_id'] ?? 0;
        
        if ($bookmark->save()) {
            return json([
                'code' => 1,
                'msg' => '更新成功',
                'data' => $bookmark,
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
     * 删除书签
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $bookmark = BookMark::find($id);
        
        if (!$bookmark) {
            return json([
                'code' => 0,
                'msg' => '书签不存在',
                'data' => null,
            ]);
        }
        
        if ($bookmark->delete()) {
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
        
        $bookmark = BookMark::find($id);
        
        if (!$bookmark) {
            return json([
                'code' => 0,
                'msg' => '书签不存在',
                'data' => null,
            ]);
        }
        
        $bookmark->status = $status;
        
        if ($bookmark->save()) {
            return json([
                'code' => 1,
                'msg' => $status == 1 ? '已启用' : ($status == 0 ? '已隐藏' : '已标记失效'),
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