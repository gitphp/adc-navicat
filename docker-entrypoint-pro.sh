#!/bin/sh
set -e
# 检查 vendor 目录是否存在（且非空）
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "vendor 目录不存在或为空，执行 composer install -vvv..."
    composer install -vvv
else
    echo "vendor 目录已存在，跳过 composer install"
fi

if [ -d "runtime" ]; then
  rm -rf ./runtime/container
fi

# 启动 Hyperf 服务
php bin/hyperf.php preStart
php bin/hyperf.php start
