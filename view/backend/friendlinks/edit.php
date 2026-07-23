<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑友情链接</title>
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
        <form class="layui-form" id="friendLinksForm">
            <input type="hidden" name="id" value="<?= $friendLink->id ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">网站名称</label>
                <div class="layui-input-block">
                    <input type="text" name="link_name" value="<?= htmlspecialchars($friendLink->link_name) ?>" placeholder="请输入网站名称" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">网站链接</label>
                <div class="layui-input-block">
                    <input type="text" name="link_url" value="<?= htmlspecialchars($friendLink->link_url) ?>" placeholder="请输入网站链接" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">网站Logo</label>
                <div class="layui-input-block">
                    <input type="text" name="link_logo" value="<?= htmlspecialchars($friendLink->link_logo) ?>" placeholder="请输入网站Logo URL" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">网站描述</label>
                <div class="layui-input-block">
                    <textarea name="link_desc" placeholder="请输入网站描述" class="layui-textarea" rows="3"><?= htmlspecialchars($friendLink->link_desc) ?></textarea>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序权重</label>
                <div class="layui-input-block">
                    <input type="number" name="link_sort" value="<?= $friendLink->link_sort ?>" min="0" placeholder="值越小越靠前" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="link_status" value="1" title="启用" <?= $friendLink->link_status == 1 ? 'checked' : '' ?>>
                    <input type="radio" name="link_status" value="0" title="禁用" <?= $friendLink->link_status == 0 ? 'checked' : '' ?>>
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
                    url: '/backend/friendlinks/update',
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