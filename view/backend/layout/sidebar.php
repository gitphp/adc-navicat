<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="/backend/index" class="brand-link">
            <span class="brand-logo">m</span>
            <span class="brand-text">8M8 后台管理</span>
        </a>
    </div>

    <!-- 快捷图标 2x2 -->
    <div class="sidebar-shortcuts">
        <div class="shortcut-item shortcut-blue" title="控制台">
            <i class="layui-icon layui-icon-home"></i>
        </div>
        <div class="shortcut-item shortcut-teal" title="编辑">
            <i class="layui-icon layui-icon-edit"></i>
        </div>
        <div class="shortcut-item shortcut-yellow" title="文件">
            <i class="layui-icon layui-icon-folder"></i>
        </div>
        <div class="shortcut-item shortcut-orange" title="节点">
            <i class="layui-icon layui-icon-component"></i>
        </div>
    </div>

    <!-- 菜单 -->
    <div class="sidebar-menu">
        <?php foreach ($menu_tree as $menu): ?>
            <?php if (isset($menu['children']) && !empty($menu['children'])): ?>
                <div class="menu-item has-children">
                    <div class="menu-header" onclick="toggleMenu('menu-<?= $menu['id'] ?>')">
                        <i class="layui-icon <?= $menu['menu_icon'] ?: 'layui-icon-app' ?>"></i>
                        <span class="menu-title"><?= htmlspecialchars($menu['menu_name']) ?></span>
                        <i class="layui-icon layui-icon-down arrow"></i>
                    </div>
                    <div class="menu-children" id="menu-<?= $menu['id'] ?>">
                        <?php foreach ($menu['children'] as $child): ?>
                            <?php if (isset($child['children']) && !empty($child['children'])): ?>
                                <div class="menu-item sub-has-children">
                                    <div class="sub-menu-header" onclick="toggleSubMenu('sub-menu-<?= $child['id'] ?>')">
                                        <span class="sub-menu-title"><?= htmlspecialchars($child['menu_name']) ?></span>
                                        <i class="layui-icon layui-icon-right sub-arrow"></i>
                                    </div>
                                    <div class="sub-menu-children" id="sub-menu-<?= $child['id'] ?>">
                                        <?php foreach ($child['children'] as $grandchild): ?>
                                            <?php $gPath = $grandchild['menu_path'] ?? ($grandchild['menu_url'] ?? '#'); ?>
                                            <div class="menu-link" data-url="<?= htmlspecialchars($gPath) ?>" onclick="loadContent('<?= htmlspecialchars($gPath) ?>', this)">
                                                <span class="menu-text"><?= htmlspecialchars($grandchild['menu_name']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $cPath = $child['menu_path'] ?? ($child['menu_url'] ?? '#'); ?>
                                <div class="menu-link" data-url="<?= htmlspecialchars($cPath) ?>" onclick="loadContent('<?= htmlspecialchars($cPath) ?>', this)">
                                    <i class="layui-icon <?= $child['menu_icon'] ?: 'layui-icon-circle' ?>"></i>
                                    <span class="menu-text"><?= htmlspecialchars($child['menu_name']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php $mPath = $menu['menu_path'] ?? ($menu['menu_url'] ?? '#'); ?>
                <div class="menu-link single" data-url="<?= htmlspecialchars($mPath) ?>" onclick="loadContent('<?= htmlspecialchars($mPath) ?>', this)">
                    <i class="layui-icon <?= $menu['menu_icon'] ?: 'layui-icon-app' ?>"></i>
                    <span class="menu-text"><?= htmlspecialchars($menu['menu_name']) ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
