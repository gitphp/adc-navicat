<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加菜单</title>
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
        .layui-form-item {
            margin-bottom: 15px;
        }
        .layui-form-label {
            width: 120px;
        }
        .layui-input-block {
            margin-left: 150px;
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
        <div class="form-title">添加菜单</div>
        
        <form class="layui-form" action="" method="post" id="menuForm">
            <div class="layui-form-item">
                <label class="layui-form-label">父级菜单</label>
                <div class="layui-input-block">
                    <select name="parent_id" id="parent_id" class="layui-input">
                        <option value="0">无（顶级菜单）</option>
                        <?php foreach ($parent_options as $option) : ?>
                        <option value="<?= $option['id'] ?>"><?= htmlspecialchars($option['menu_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">菜单名称 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="text" name="menu_name" id="menu_name" required lay-verify="required" 
                           placeholder="请输入菜单名称" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">菜单图标</label>
                <div class="layui-input-block">
                    <input type="text" name="menu_icon" id="menu_icon" 
                           placeholder="如：el-icon-user" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">路由路径</label>
                <div class="layui-input-block">
                    <input type="text" name="menu_path" id="menu_path" 
                           placeholder="如：/user/list" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">组件路径</label>
                <div class="layui-input-block">
                    <input type="text" name="component" id="component" 
                           placeholder="如：user/Index" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">权限标识</label>
                <div class="layui-input-block">
                    <input type="text" name="permission_code" id="permission_code" 
                           placeholder="关联的权限标识，用于按钮级控制" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序号</label>
                <div class="layui-input-block">
                    <input type="number" name="menu_sort" id="menu_sort" value="0" 
                           placeholder="请输入排序号" class="layui-input" style="width: 150px;">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">菜单状态 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="radio" name="menu_status" value="1" title="启用" checked>
                    <input type="radio" name="menu_status" value="0" title="禁用">
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
            
            // 提交表单
            function submitForm() {
                var menuName = document.getElementById('menu_name').value.trim();
                
                if (!menuName) {
                    layer.msg('请输入菜单名称', {icon: 5});
                    return;
                }
                
                // 获取表单数据
                var formData = {
                    parent_id: document.getElementById('parent_id').value,
                    menu_name: menuName,
                    menu_icon: document.getElementById('menu_icon').value,
                    menu_path: document.getElementById('menu_path').value,
                    component: document.getElementById('component').value,
                    permission_code: document.getElementById('permission_code').value,
                    menu_sort: document.getElementById('menu_sort').value,
                    menu_status: form.val('menuForm').menu_status,
                };
                
                layui.$.ajax({
                    url: '/backend/menu/save',
                    type: 'POST',
                    data: formData,
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
</body>
</html>
