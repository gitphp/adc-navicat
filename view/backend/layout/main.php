<?php include 'head.php'; ?>

<!-- 侧边栏 -->
<?php include 'sidebar.php'; ?>

<!-- 主区域：顶栏 + 标签 + 内容 + 页脚 -->
<div class="main-container" id="main-container">
    <?php include 'header.php'; ?>

    <div class="page-content" id="page-content">
        <div class="content-wrapper" id="content-wrapper">
            <?= $content ?>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</div>

<script src="/static/backend/layui/layui.js"></script>
<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        var main = document.getElementById('main-container');
        sidebar.classList.toggle('collapsed');
        main.classList.toggle('collapsed');
    }

    function toggleMenu(id) {
        var el = document.getElementById(id);
        var header = el.parentElement.querySelector('.menu-header');
        if (el.classList.contains('open')) {
            el.classList.remove('open');
            header.classList.remove('open');
        } else {
            el.classList.add('open');
            header.classList.add('open');
        }
    }

    function toggleSubMenu(id) {
        var el = document.getElementById(id);
        var header = el.parentElement.querySelector('.sub-menu-header');
        if (el.classList.contains('open')) {
            el.classList.remove('open');
            header.classList.remove('open');
        } else {
            el.classList.add('open');
            header.classList.add('open');
        }
    }

    function scrollTabs(direction) {
        var tabsNav = document.getElementById('tabsNav');
        var amount = 180;
        tabsNav.scrollBy(direction === 'left' ? -amount : amount, 0);
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    function toggleUserMenu(e) {
        e.stopPropagation();
        document.getElementById('userDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function() {
        var dd = document.getElementById('userDropdown');
        if (dd) dd.classList.remove('show');
    });

    function refreshContent() {
        var active = document.querySelector('.tab-item.active');
        if (active) {
            var url = active.getAttribute('data-url');
            delete tabContentCache[url];
            loadContentByUrl(url);
        }
    }

    // 标签页内容缓存：只存服务端原始 HTML
    var tabContentCache = {};
    var activeTabUrl = '';

    function addTab(url, title) {
        var tabsNav = document.getElementById('tabsNav');
        var existing = tabsNav.querySelector('.tab-item[data-url="' + url + '"]');
        if (existing) {
            switchTab(existing);
            return;
        }

        var tab = document.createElement('div');
        tab.className = 'tab-item';
        tab.setAttribute('data-url', url);
        tab.setAttribute('onclick', 'switchTab(this)');
        tab.innerHTML = '<span>' + title + '</span>' +
            '<i class="layui-icon layui-icon-close tab-close" onclick="event.stopPropagation();closeTab(this)"></i>';
        tabsNav.appendChild(tab);
        switchTab(tab);
        tab.scrollIntoView({ inline: 'nearest', block: 'nearest' });
    }

    function switchTab(tabElement) {
        var url = tabElement.getAttribute('data-url');
        if (activeTabUrl === url) return;

        document.querySelectorAll('#tabsNav .tab-item').forEach(function(t) {
            t.classList.remove('active');
        });
        tabElement.classList.add('active');
        activeTabUrl = url;
        loadContentByUrl(url);
    }

    function closeTab(closeIcon) {
        var tab = closeIcon.parentElement;
        var url = tab.getAttribute('data-url');
        var tabs = document.querySelectorAll('#tabsNav .tab-item');
        if (tabs.length <= 1) return;

        if (tab.classList.contains('active')) {
            var next = tab.nextElementSibling || tab.previousElementSibling;
            if (next) switchTab(next);
        }
        tab.remove();
        delete tabContentCache[url];
    }

    function syncMenuActive(url) {
        document.querySelectorAll('.menu-link').forEach(function(item) {
            item.classList.remove('active');
        });
        document.querySelectorAll('.menu-header').forEach(function(item) {
            item.classList.remove('active');
        });

        var activeLink = null;
        document.querySelectorAll('.menu-link').forEach(function(item) {
            if (item.getAttribute('data-url') === url) {
                item.classList.add('active');
                activeLink = item;
            }
        });

        if (activeLink) {
            var parent = activeLink.parentElement;
            while (parent && parent !== document.body) {
                if (parent.classList.contains('has-children') || parent.classList.contains('menu-item')) {
                    var kids = parent.children;
                    for (var i = 0; i < kids.length; i++) {
                        if (kids[i].classList.contains('menu-header')) kids[i].classList.add('open');
                        if (kids[i].classList.contains('menu-children')) kids[i].classList.add('open');
                        if (kids[i].classList.contains('sub-menu-header')) kids[i].classList.add('open');
                        if (kids[i].classList.contains('sub-menu-children')) kids[i].classList.add('open');
                    }
                }
                parent = parent.parentElement;
            }
        }
    }

    function loadContentByUrl(url) {
        if (!url || url === '#') return;
        syncMenuActive(url);

        var wrapper = document.getElementById('content-wrapper');
        
        // 命中缓存：还原原始 HTML 并重新执行（保留 text/html 模板）
        if (tabContentCache[url]) {
            wrapper.innerHTML = tabContentCache[url];
            executeScripts(wrapper);
            return;
        }

        wrapper.innerHTML = '<div class="loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate"></i> 加载中...</div>';

        layui.$.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(html) {
                tabContentCache[url] = html;
                wrapper.innerHTML = html;
                executeScripts(wrapper);
            },
            error: function() {
                wrapper.innerHTML = '<div class="loading">加载失败，请刷新页面重试</div>';
            }
        });
    }

    function loadContent(url, element) {
        if (!url || url === '#') return;
        var title = '页面';
        if (element) {
            var textSpan = element.querySelector('.menu-text');
            if (textSpan) title = textSpan.textContent;
        }
        addTab(url, title);
    }

    // 仅执行 JS；保留 type="text/html" 的 laytpl 模板，否则操作列/状态列会空白
    function executeScripts(container) {
        var scripts = Array.prototype.slice.call(container.querySelectorAll('script'));
        scripts.forEach(function(script) {
            var type = (script.getAttribute('type') || 'text/javascript').toLowerCase();
            if (type === 'text/html') {
                return;
            }
            var newScript = document.createElement('script');
            if (script.src) {
                newScript.src = script.src;
                newScript.onload = function() { initLayuiComponents(); };
            } else {
                newScript.text = script.text || script.textContent || '';
            }
            script.parentNode.replaceChild(newScript, script);
        });
        initLayuiComponents();
    }

    function initLayuiComponents() {
        layui.use(['form', 'table', 'layer'], function() {
            var form = layui.form;
            form.render();
            window.layer = layui.layer;
            window.form = form;
            window.table = layui.table;
        });
    }

    function logout() {
        if (confirm('确定要退出登录吗？')) {
            window.location.href = '/backend/login/logout';
        }
    }

    var treeMobile = document.querySelector('.site-tree-mobile');
    var shadeMobile = document.querySelector('.site-mobile-shade');
    if (treeMobile) {
        treeMobile.addEventListener('click', function() {
            document.body.classList.add('site-mobile');
        });
    }
    if (shadeMobile) {
        shadeMobile.addEventListener('click', function() {
            document.body.classList.remove('site-mobile');
        });
    }

    var fixbarTop = document.querySelector('.layui-fixbar-top');
    if (fixbarTop) {
        fixbarTop.style.display = 'none';
        window.addEventListener('scroll', function() {
            fixbarTop.style.display = window.scrollY > 100 ? 'block' : 'none';
        }, true);
        fixbarTop.addEventListener('click', function() {
            var page = document.getElementById('page-content');
            if (page) page.scrollTo({ top: 0, behavior: 'smooth' });
            else window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // 首屏根据当前路径同步菜单与标签
    (function initCurrentPage() {
        var path = window.location.pathname;
        if (!path || path === '/' || path.indexOf('/backend/index') === 0) return;

        var title = document.title || '页面';
        var match = document.querySelector('.menu-link[data-url="' + path + '"]');
        if (match) {
            var textSpan = match.querySelector('.menu-text');
            if (textSpan) title = textSpan.textContent;
        }

        var tabsNav = document.getElementById('tabsNav');
        var home = tabsNav.querySelector('.tab-item');
        if (home) home.classList.remove('active');

        var tab = document.createElement('div');
        tab.className = 'tab-item active';
        tab.setAttribute('data-url', path);
        tab.setAttribute('onclick', 'switchTab(this)');
        tab.innerHTML = '<span>' + title + '</span>' +
            '<i class="layui-icon layui-icon-close tab-close" onclick="event.stopPropagation();closeTab(this)"></i>';
        tabsNav.appendChild(tab);
        syncMenuActive(path);
        activeTabUrl = path;
        // 首屏内容已由浏览器渲染，不写入缓存；离开后再进入时走 AJAX 拉取
    })();
</script>
</body>
</html>
