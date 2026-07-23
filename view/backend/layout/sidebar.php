<div class="sidebar">
    <div class="sidebar-menu">
        <?php foreach ($menu_tree as $menu): ?>
            <?php if (isset($menu['children']) && !empty($menu['children'])): ?>
                <!-- 有子菜单的菜单项 -->
                <div class="menu-item has-children">
                    <div class="menu-header" onclick="toggleMenu('menu-<?= $menu['id'] ?>')">
                        <i class="layui-icon <?= $menu['menu_icon'] ?: 'layui-icon-app' ?>"></i>
                        <span class="menu-title"><?= htmlspecialchars($menu['menu_name']) ?></span>
                        <i class="layui-icon layui-icon-down arrow"></i>
                    </div>
                    <div class="menu-children" id="menu-<?= $menu['id'] ?>">
                        <?php foreach ($menu['children'] as $child): ?>
                            <?php if (isset($child['children']) && !empty($child['children'])): ?>
                                <!-- 三级菜单 -->
                                <div class="menu-item sub-has-children">
                                    <div class="sub-menu-header" onclick="toggleSubMenu('sub-menu-<?= $child['id'] ?>')">
                                        <span class="sub-menu-title"><?= htmlspecialchars($child['menu_name']) ?></span>
                                        <i class="layui-icon layui-icon-right sub-arrow"></i>
                                    </div>
                                    <div class="sub-menu-children" id="sub-menu-<?= $child['id'] ?>">
                                        <?php foreach ($child['children'] as $grandchild): ?>
                                            <div class="menu-link" onclick="loadContent('<?= $grandchild['menu_path'] ?: '#' ?>', this)">
                                                <span class="menu-text"><?= htmlspecialchars($grandchild['menu_name']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- 二级菜单 -->
                                <div class="menu-link" onclick="loadContent('<?= $child['menu_path'] ?: '#' ?>', this)">
                                    <i class="layui-icon <?= $child['menu_icon'] ?: 'layui-icon-circle' ?>"></i>
                                    <span class="menu-text"><?= htmlspecialchars($child['menu_name']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- 无子菜单的菜单项 -->
                <div class="menu-link single" onclick="loadContent('<?= $menu['menu_path'] ?: '#' ?>', this)">
                    <i class="layui-icon <?= $menu['menu_icon'] ?: 'layui-icon-app' ?>"></i>
                    <span class="menu-text"><?= htmlspecialchars($menu['menu_name']) ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>