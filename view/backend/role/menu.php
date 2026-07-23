<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>角色菜单配置</title>
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
        .role-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .role-info label {
            display: inline-block;
            width: 80px;
            color: #666;
        }
        .tree-container {
            max-height: 450px;
            overflow-y: auto;
        }
        .tree-node {
            padding-left: 20px;
        }
        .tree-node.level-0 {
            padding-left: 0;
        }
        .tree-node.level-1 {
            padding-left: 20px;
        }
        .tree-node.level-2 {
            padding-left: 40px;
        }
        .tree-node.level-3 {
            padding-left: 60px;
        }
        .tree-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        .tree-item input[type="checkbox"] {
            margin-right: 8px;
        }
        .tree-item .menu-icon {
            margin-right: 8px;
            color: #999;
        }
        .tree-item .menu-name {
            flex: 1;
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
        <div class="form-title">角色菜单配置</div>
        
        <div class="role-info">
            <label>角色名称：</label><span><?= htmlspecialchars($role->role_name) ?></span>&nbsp;&nbsp;
            <label>角色标识：</label><span><?= htmlspecialchars($role->role_code) ?></span>
        </div>
        
        <form class="layui-form" action="" method="post" id="menuForm">
            <input type="hidden" name="role_id" value="<?= htmlspecialchars($role->id) ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">选择菜单</label>
                <div class="layui-input-block">
                    <div class="tree-container">
                        <?php echo $this->renderMenuTree($menus, $menu_ids); ?>
                    </div>
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
                var menuIds = [];
                var checkboxes = document.querySelectorAll('input[name="menu_ids[]"]:checked');
                checkboxes.forEach(function(checkbox) {
                    menuIds.push(checkbox.value);
                });
                
                layui.$.ajax({
                    url: '/backend/role/saveMenu',
                    type: 'POST',
                    data: {
                        role_id: document.querySelector('input[name="role_id"]').value,
                        menu_ids: menuIds,
                    },
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
    
    <?php
    /**
     * 递归渲染菜单树
     * @param array $menus 菜单数据
     * @param array $selectedIds 已选中的菜单ID
     * @param int $level 当前层级
     */
    function renderMenuTree(array $menus, array $selectedIds, int $level = 0): void
    {
        foreach ($menus as $menu) {
            $checked = in_array($menu['id'], $selectedIds) ? 'checked' : '';
            $hasChildren = isset($menu['children']) && !empty($menu['children']);
            
            echo '<div class="tree-node level-' . $level . '">';
            echo '<div class="tree-item">';
            echo '<input type="checkbox" name="menu_ids[]" value="' . $menu['id'] . '" ' . $checked . ' lay-skin="primary" title="">';
            if ($menu['menu_icon']) {
                echo '<span class="menu-icon">' . htmlspecialchars($menu['menu_icon']) . '</span>';
            }
            echo '<span class="menu-name">' . htmlspecialchars($menu['menu_name']) . '</span>';
            echo '</div>';
            
            if ($hasChildren) {
                renderMenuTree($menu['children'], $selectedIds, $level + 1);
            }
            
            echo '</div>';
        }
    }
    
    // 渲染菜单树
    echo '<script type="text/javascript">';
    echo 'function renderMenuTree(menus, selectedIds, level) {';
    echo 'var html = "";';
    echo 'for (var i = 0; i < menus.length; i++) {';
    echo 'var menu = menus[i];';
    echo 'var checked = selectedIds.indexOf(menu.id) > -1 ? "checked" : "";';
    echo 'var hasChildren = menu.children && menu.children.length > 0;';
    echo 'html += \'<div class="tree-node level-\' + level + \'">';
    echo '<div class="tree-item">';
    echo '<input type="checkbox" name="menu_ids[]" value="\' + menu.id + \'" \' + checked + \' lay-skin="primary" title="">';
    echo 'if (menu.menu_icon) {';
    echo 'html += \'<span class="menu-icon">\'+menu.menu_icon+\'</span>\';';
    echo '}';
    echo 'html += \'<span class="menu-name">\'+menu.menu_name+\'</span>\';';
    echo '</div>\';';
    echo 'if (hasChildren) {';
    echo 'html += renderMenuTree(menu.children, selectedIds, level + 1);';
    echo '}';
    echo 'html += \'</div>\';';
    echo '}';
    echo 'return html;';
    echo '}';
    echo '</script>';
    ?>
</body>
</html>
