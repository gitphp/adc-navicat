#!/bin/bash

DOCKER_NAME='adc-merchant'
# 直接在容器内执行命令（多条命令用 && 连接）
docker exec -i $DOCKER_NAME bash -c "
  composer cs-fix
"