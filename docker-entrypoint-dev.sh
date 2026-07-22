#!/bin/sh
set -e

# ===================== 优化1：处理composer.lock丢失场景 =====================
# 检查composer.lock是否存在，不存在则先生成（优先用--lock，避免更新依赖）
if [ ! -f "composer.lock" ]; then
    echo "composer.lock文件不存在，尝试重新生成..."
    # 先尝试仅生成lock文件（不改动依赖），失败则执行install生成
    if ! composer update --lock; then
        echo "composer update --lock失败，执行composer install生成lock..."
        composer install -vvv
    fi
fi

# ===================== 优化2：原vendor检查逻辑（保留，增加日志） =====================
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "vendor 目录不存在或为空，执行 composer install -vvv..."
    composer install -vvv
else
    echo "vendor 目录已存在，跳过 composer install"
fi

# ===================== 优化3：清理runtime更彻底（开发环境） =====================
if [ -d "runtime" ]; then
  echo "清理runtime缓存目录..."
  rm -rf ./runtime/*  # 清理所有runtime缓存，而非仅container
fi

# ===================== 优化4：兼容macOS的sed命令（原脚本仅兼容Linux） =====================
# macOS的sed -i需要加备份后缀，这里做兼容处理
SED_CMD="sed -i"
if [ "$(uname)" = "Darwin" ]; then
    SED_CMD="sed -i ''"
fi
$SED_CMD 's/\r$//' *.sh
[ -f ".githooks/pre-commit" ] && $SED_CMD 's/\r$//' .githooks/pre-commit

# ===================== 优化5：容错git钩子配置 =====================
echo "配置git提交钩子..."
if ! composer set-cs-fix-githooks; then
    echo "git钩子配置失败（可能未安装php-cs-fixer），跳过..."
fi

echo "开发环境启动Hyperf服务（热重启）..."
composer dump-autoload
php bin/hyperf.php preStart
php bin/hyperf.php server:watch