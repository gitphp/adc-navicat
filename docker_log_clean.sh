#!/bin/bash

# 优先级：环境变量 DOCKER_NAME > 命令行第一个参数 > 当前容器 hostname
TARGET_CONTAINER="${DOCKER_NAME:-$1}"

if [ -z "$TARGET_CONTAINER" ]; then
    TARGET_CONTAINER=$(hostname)
fi

# 获取目标容器的日志路径
log=$(docker inspect --format='{{.LogPath}}' "$TARGET_CONTAINER" 2>/dev/null)

if [ -z "$log" ]; then
    echo "未找到容器($TARGET_CONTAINER)的日志路径"
    exit 1
fi

if [ -f "$log" ]; then
    truncate -s 0 "$log"
    echo "已清理容器($TARGET_CONTAINER)日志: $log"
else
    echo "容器($TARGET_CONTAINER)日志文件不存在"
    exit 1
fi