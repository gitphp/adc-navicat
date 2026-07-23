<div class="content-header">
    <div class="content-title">欢迎回来，<?= htmlspecialchars($user_info['user_nick']) ?></div>
</div>

<div class="welcome-card">
    <div class="welcome-title">系统管理后台</div>
    <div class="welcome-desc">这是您的系统管理后台，您可以在这里管理系统的各项功能。</div>
</div>

<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 40px;
        color: #fff;
        margin-bottom: 20px;
    }
    .welcome-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .welcome-desc {
        font-size: 16px;
        opacity: 0.9;
    }
</style>