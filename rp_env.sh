#!/bin/bash
# rp_env.sh
# 用于批量更新 .env 配置文件的可复用模板
# 使用方法: chmod +x rp_env.sh && ./rp_env.sh

set -e  # 出错立即退出

# ========= 配置区域 =========
# APP 环境
APP_ENV="dev"

# 数据库
DB_HOST="mysql8"
DB_PORT="3306"
DB_USERNAME="root"
DB_PASSWORD="123456"

# Redis
REDIS_HOST="redis"
REDIS_PORT="6379"
REDIS_AUTH="123456"

# Nacos
NACOS_IP="nacos"
NACOS_PORT="8848"
NACOS_USER="nacos"
NACOS_PWD="nacos"
NACOS_GROUP_NAME="adc"
NACOS_NAME_SPACE_ID="21b72369-f87e-43fc-867f-b6bb530630ae"

# RabbitMQ
AMQP_HOST="rabbitmq"
AMQP_PORT="5672"
AMQP_USER="admin"
AMQP_PASSWORD="admin"

# XXL-Job
XXL_JOB_ADMIN_ADDRESS="http://xxl-job.adc.com:8089/xxl-job-admin"



# ========= 检查 & 备份 =========
if [ ! -f .env ]; then
    echo "❌ .env 文件不存在，请先创建！"
    exit 1
fi

# ========= 替换函数 =========
update_env_var() {
    local key="$1"
    local value="$2"
    sed -i "s@^${key}=.*@${key}=${value}@" .env
}

# ========= 开始替换 =========
# 数据库
update_env_var "DB_HOST" "$DB_HOST"
update_env_var "DB_PORT" "$DB_PORT"
update_env_var "DB_USERNAME" "$DB_USERNAME"
update_env_var "DB_PASSWORD" "$DB_PASSWORD"

# Redis
update_env_var "REDIS_HOST" "$REDIS_HOST"
update_env_var "REDIS_PORT" "$REDIS_PORT"
update_env_var "REDIS_AUTH" "$REDIS_AUTH"

# Nacos
update_env_var "NACOS_IP" "$NACOS_IP"
update_env_var "NACOS_PORT" "$NACOS_PORT"
update_env_var "NACOS_USER" "$NACOS_USER"
update_env_var "NACOS_PWD" "$NACOS_PWD"
update_env_var "NACOS_GROUP_NAME" "$NACOS_GROUP_NAME"
update_env_var "NACOS_NAME_SPACE_ID" "$NACOS_NAME_SPACE_ID"

# APP
update_env_var "APP_ENV" "$APP_ENV"

# RabbitMQ
update_env_var "AMQP_HOST" "$AMQP_HOST"
update_env_var "AMQP_PORT" "$AMQP_PORT"
update_env_var "AMQP_USER" "$AMQP_USER"
update_env_var "AMQP_PASSWORD" "$AMQP_PASSWORD"

# XXL-Job
update_env_var "XXL_JOB_ADMIN_ADDRESS" "$XXL_JOB_ADMIN_ADDRESS"



echo "✅ .env 配置更新完成！"

# ========= 显示更新后的关键配置 =========
echo "📄 更新后的配置："
grep -E 'DB_|REDIS_|NACOS_|APP_ENV|AMQP_|XXL_' .env
