<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加书签</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .form-item {
            margin-bottom: 15px;
        }
        .form-label {
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 10px;
            color: #666;
            font-weight: 500;
        }
        .form-input {
            width: calc(100% - 120px);
            display: inline-block;
        }
        .form-actions {
            margin-top: 20px;
            text-align: center;
        }
        .layui-form-item {
            margin-bottom: 15px;
        }
        .layui-form-label {
            width: 100px;
        }
        .layui-input-block {
            margin-left: 130px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form class="layui-form" id="bookmarkForm">
            <div class="layui-form-item">
                <label class="layui-form-label">所属分类</label>
                <div class="layui-input-block">
                    <select name="category_id" lay-verify="required" lay-search>
                        <option value="0">默认书签栏</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">短标题</label>
                <div class="layui-input-block">
                    <input type="text" name="short_title" placeholder="请输入短标题（16字符以内）" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">长标题</label>
                <div class="layui-input-block">
                    <input type="text" name="book_title" placeholder="请输入长标题（128字符以内）" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">链接地址</label>
                <div class="layui-input-block">
                    <input type="text" name="book_url" placeholder="请输入链接地址" lay-verify="required|url" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">网站图标</label>
                <div class="layui-input-block">
                    <input type="text" name="book_favicon" placeholder="请输入网站图标URL" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">书签描述</label>
                <div class="layui-input-block">
                    <textarea name="book_desc" placeholder="请输入书签描述" class="layui-textarea" rows="3"></textarea>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序权重</label>
                <div class="layui-input-block">
                    <input type="number" name="sort_order" value="0" min="0" placeholder="值越小越靠前" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="正常" checked>
                    <input type="radio" name="status" value="0" title="隐藏">
                    <input type="radio" name="status" value="2" title="失效">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">加粗显示</label>
                <div class="layui-input-block">
                    <input type="radio" name="is_bold" value="0" title="否" checked>
                    <input type="radio" name="is_bold" value="1" title="是">
                </div>
            </div>
            
            <div class="form-actions">
                <button class="layui-btn" lay-submit lay-filter="save">保存</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">取消</button>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            // 监听表单提交
            form.on('submit(save)', function(data) {
                layui.$.ajax({
                    url: '/backend/bookmark/save',
                    type: 'POST',
                    data: data.field,
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            setTimeout(function() {
                                parent.layer.closeAll();
                            }, 1000);
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    error: function() {
                        layer.msg('保存失败', {icon: 5});
                    }
                });
                return false;
            });
        });
    </script>
</body>
</html>