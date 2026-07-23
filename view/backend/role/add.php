<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加角色</title>
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
        <div class="form-title">添加角色</div>
        
        <form class="layui-form" action="" method="post" id="roleForm">
            <div class="layui-form-item">
                <label class="layui-form-label">角色名称 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="text" name="role_name" id="role_name" required lay-verify="required" 
                           placeholder="请输入角色名称" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">角色标识 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="text" name="role_code" id="role_code" required lay-verify="required" 
                           placeholder="请输入角色标识（如：admin）" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">角色类型 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <select name="role_type" id="role_type" required lay-verify="required" class="layui-input">
                        <?php foreach ($role_type_options as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">数据权限范围 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <select name="data_scope" id="data_scope" required lay-verify="required" class="layui-input">
                        <?php foreach ($data_scope_options as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序号</label>
                <div class="layui-input-block">
                    <input type="number" name="role_sort" id="role_sort" value="0" 
                           placeholder="请输入排序号" class="layui-input" style="width: 150px;">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">角色状态 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="radio" name="role_status" value="1" title="启用" checked>
                    <input type="radio" name="role_status" value="0" title="禁用">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">角色备注</label>
                <div class="layui-input-block">
                    <textarea name="role_remark" id="role_remark" placeholder="请输入角色备注" 
                              class="layui-textarea" style="width: 400px; height: 100px;"></textarea>
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
            window.submitForm = function() {
                var roleName = document.getElementById('role_name').value.trim();
                var roleCode = document.getElementById('role_code').value.trim();
                
                if (!roleName) {
                    layer.msg('请输入角色名称', {icon: 5});
                    return;
                }
                
                if (!roleCode) {
                    layer.msg('请输入角色标识', {icon: 5});
                    return;
                }
                
                // 获取表单数据
                var formData = {
                    role_name: roleName,
                    role_code: roleCode,
                    role_type: document.getElementById('role_type').value,
                    data_scope: document.getElementById('data_scope').value,
                    role_sort: document.getElementById('role_sort').value,
                    role_status: form.val('roleForm').role_status,
                    role_remark: document.getElementById('role_remark').value,
                };
                
                layui.$.ajax({
                    url: '/backend/role/save',
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
            window.closeDialog = function() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        });
    </script>
</body>
</html>
