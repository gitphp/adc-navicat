#!/bin/bash
DOCKER_NAME='adc-merchant'
rm -rf ./runtime/container
docker exec -i $DOCKER_NAME bash -c '
  composer set-cs-fix-githooks &&
  php bin/hyperf.php preStart
'
docker restart $DOCKER_NAME