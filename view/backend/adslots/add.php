<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加广告位</title>
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
        <form class="layui-form" id="adSlotsForm">
            <div class="layui-form-item">
                <label class="layui-form-label">广告位编码</label>
                <div class="layui-input-block">
                    <input type="text" name="slot_code" placeholder="如：home_banner_top" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">广告位名称</label>
                <div class="layui-input-block">
                    <input type="text" name="slot_name" placeholder="如：首页顶部轮播图" lay-verify="required" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">广告位描述</label>
                <div class="layui-input-block">
                    <textarea name="description" placeholder="请输入广告位描述" class="layui-textarea" rows="3"></textarea>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">宽度(像素)</label>
                <div class="layui-input-block">
                    <input type="number" name="width" value="0" min="0" placeholder="广告位宽度" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">高度(像素)</label>
                <div class="layui-input-block">
                    <input type="number" name="height" value="0" min="0" placeholder="广告位高度" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">最大展示数量</label>
                <div class="layui-input-block">
                    <input type="number" name="max_items" value="1" min="1" placeholder="最大展示数量" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">是否系统预设</label>
                <div class="layui-input-block">
                    <input type="radio" name="is_system" value="0" title="否" checked>
                    <input type="radio" name="is_system" value="1" title="是">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="slot_status" value="1" title="启用" checked>
                    <input type="radio" name="slot_status" value="0" title="禁用">
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
                    url: '/backend/adslots/save',
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