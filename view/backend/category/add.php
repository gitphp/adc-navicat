<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加分类</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            padding: 20px;
            background: #f5f7fa;
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-title">添加分类</div>
        
        <form class="layui-form" action="" method="post" id="categoryForm">
            <div class="layui-form-item">
                <label class="layui-form-label">父级分类</label>
                <div class="layui-input-block">
                    <select name="parent_id" lay-verify="required">
                        <option value="0">顶级分类</option>
                        <?php echo $this->renderCategoryOptions($categories, 0, 0); ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                    <input type="text" name="category_name" lay-verify="required" placeholder="请输入分类名称" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">可见性类型</label>
                <div class="layui-input-block">
                    <select name="show_type">
                        <option value="0">全部可见</option>
                        <option value="1">指定客户可见</option>
                        <option value="2">指定客户不可见</option>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="cat_status" value="1" title="显示" checked>
                    <input type="radio" name="cat_status" value="0" title="隐藏">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="number" name="sort_order" value="0" min="0" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">分类描述</label>
                <div class="layui-input-block">
                    <textarea name="description" placeholder="请输入分类描述（SEO说明）" class="layui-textarea"></textarea>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea name="cat_remark" placeholder="请输入备注信息" class="layui-textarea"></textarea>
                </div>
            </div>
            
            <div class="form-footer">
                <button type="button" class="layui-btn" onclick="submitForm()">提交</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="closeDialog()">取消</button>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            form.render();
            
            // 设置默认父级分类
            var parentId = '<?= $parent_id ?>';
            if (parentId > 0) {
                form.val('categoryForm', {
                    parent_id: parentId
                });
            }
            
            // 提交表单
            function submitForm() {
                form.verify({
                    category_name: function(value) {
                        if (!value) {
                            return '请输入分类名称';
                        }
                    }
                });
                
                if (!form.checkValid()) {
                    return;
                }
                
                layui.$.ajax({
                    url: '/backend/category/save',
                    type: 'POST',
                    data: form.val('categoryForm'),
                    dataType: 'json',
                    beforeSend: function() {
                        layer.load(2);
                    },
                    success: function(res) {
                        layer.closeAll('loading');
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1, time: 1000}, function() {
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    error: function() {
                        layer.closeAll('loading');
                        layer.msg('提交失败', {icon: 5});
                    }
                });
            }
            
            // 关闭弹窗
            function closeDialog() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        });
    </script>
    
    <?php
    /**
     * 递归渲染分类选项
     * @param array $categories 分类数据
     * @param int $parentId 父级ID
     * @param int $level 当前层级
     */
    function renderCategoryOptions(array $categories, int $parentId, int $level): string
    {
        $html = '';
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                // 限制最大层级
                if ($level >= 2) {
                    continue;
                }
                
                $prefix = str_repeat('└── ', $level);
                $html .= '<option value="' . $category['id'] . '">' . $prefix . htmlspecialchars($category['category_name']) . '</option>';
                
                if (isset($category['children']) && !empty($category['children'])) {
                    $html .= renderCategoryOptions($category['children'], $category['id'], $level + 1);
                }
            }
        }
        return $html;
    }
    ?>
</body>
</html>
