#!/bin/bash

DOCKER_NAME='adc-merchant'
git fetch origin

BRANCH=main
LOCAL=$(git log $BRANCH -n 1 --pretty=format:"%H")
REMOTE=$(git log remotes/origin/$BRANCH -n 1 --pretty=format:"%H")

if [ $LOCAL = $REMOTE ]; then
   echo "无需更新代码"
else
    echo "需要更新，正在准备更新..."
    git pull
    rm -rf ./runtime/container
    docker exec -i $DOCKER_NAME php bin/hyperf.php preStart
    docker restart $DOCKER_NAME
fi
