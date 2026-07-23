<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑配置</title>
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
            width: 120px;
        }
        .layui-input-block {
            margin-left: 150px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form class="layui-form" id="configForm">
            <input type="hidden" name="id" value="<?= $config->id ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">配置分组</label>
                <div class="layui-input-block">
                    <select name="conf_group" lay-verify="required" class="layui-input">
                        <?php foreach ($groups as $key => $name): ?>
                        <option value="<?= $key ?>" <?= $config->conf_group == $key ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">配置键名</label>
                <div class="layui-input-block">
                    <input type="text" name="conf_key" value="<?= htmlspecialchars($config->conf_key) ?>" placeholder="请输入配置键名" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">配置说明</label>
                <div class="layui-input-block">
                    <input type="text" name="conf_desc" value="<?= htmlspecialchars($config->conf_desc) ?>" placeholder="请输入配置说明" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">输入类型</label>
                <div class="layui-input-block">
                    <select name="input_type" class="layui-input">
                        <option value="text" <?= $config->input_type == 'text' ? 'selected' : '' ?>>文本输入</option>
                        <option value="textarea" <?= $config->input_type == 'textarea' ? 'selected' : '' ?>>文本域</option>
                        <option value="image" <?= $config->input_type == 'image' ? 'selected' : '' ?>>图片上传</option>
                        <option value="file" <?= $config->input_type == 'file' ? 'selected' : '' ?>>文件上传</option>
                        <option value="json" <?= $config->input_type == 'json' ? 'selected' : '' ?>>JSON格式</option>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">配置值</label>
                <div class="layui-input-block">
                    <textarea name="conf_value" placeholder="请输入配置值" class="layui-textarea" rows="3"><?= htmlspecialchars($config->conf_value) ?></textarea>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序权重</label>
                <div class="layui-input-block">
                    <input type="number" name="conf_sort" value="<?= $config->conf_sort ?>" min="0" placeholder="值越小越靠前" class="layui-input">
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
                    url: '/backend/siteconfigs/update',
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