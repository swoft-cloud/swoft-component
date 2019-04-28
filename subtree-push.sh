#!/bin/sh

set -ex
git pull --no-edit

git subtree push --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline.git 1.x --squash
git subtree push --prefix=src/view git@github.com:swoft-cloud/swoft-view.git 1.x --squash
git subtree push --prefix=src/http-server git@github.com:swoft-cloud/swoft-http-server.git 1.x --squash
git subtree push --prefix=src/websocket-server git@github.com:swoft-cloud/swoft-websocket-server.git 1.x --squash
git subtree push --prefix=src/task git@github.com:swoft-cloud/swoft-task.git 1.x --squash
git subtree push --prefix=src/session git@github.com:swoft-cloud/swoft-session.git 1.x --squash
git subtree push --prefix=src/service-governance git@github.com:swoft-cloud/swoft-service-governance.git 1.x --squash
git subtree push --prefix=src/rpc-server git@github.com:swoft-cloud/swoft-rpc-server.git 1.x --squash
git subtree push --prefix=src/rpc-client git@github.com:swoft-cloud/swoft-rpc-client.git 1.x --squash
git subtree push --prefix=src/rpc git@github.com:swoft-cloud/swoft-rpc.git 1.x --squash
git subtree push --prefix=src/redis git@github.com:swoft-cloud/swoft-redis.git 1.x --squash
git subtree push --prefix=src/process git@github.com:swoft-cloud/swoft-process.git 1.x --squash
git subtree push --prefix=src/memory git@github.com:swoft-cloud/swoft-memory.git 1.x --squash
git subtree push --prefix=src/i18n git@github.com:swoft-cloud/swoft-i18n.git 1.x --squash
git subtree push --prefix=src/http-client git@github.com:swoft-cloud/swoft-http-client.git 1.x --squash
git subtree push --prefix=src/http-message git@github.com:swoft-cloud/swoft-http-message.git 1.x --squash
git subtree push --prefix=src/devtool git@github.com:swoft-cloud/swoft-devtool.git 1.x --squash
git subtree push --prefix=src/db git@github.com:swoft-cloud/swoft-db.git 1.x --squash
git subtree push --prefix=src/data-parser git@github.com:swoft-cloud/swoft-data-parser.git 1.x --squash
git subtree push --prefix=src/console git@github.com:swoft-cloud/swoft-console.git 1.x --squash
git subtree push --prefix=src/cache git@github.com:swoft-cloud/swoft-cache.git 1.x --squash
git subtree push --prefix=src/trace git@github.com:swoft-cloud/swoft-trace.git 1.x --squash
git subtree push --prefix=src/queue git@github.com:swoft-cloud/swoft-queue.git 1.x --squash
git subtree push --prefix=src/whoops git@github.com:swoft-cloud/swoft-whoops.git 1.x --squash
git subtree push --prefix=src/log git@github.com:swoft-cloud/swoft-log.git 1.x --squash
git subtree push --prefix=src/auth git@github.com:swoft-cloud/swoft-auth.git 1.x --squash
git subtree push --prefix=src/swagger git@github.com:swoft-cloud/swoft-swagger.git 1.x --squash
git subtree push --prefix=src/framework git@github.com:swoft-cloud/swoft-framework.git 1.x --squash
git subtree push --prefix=src/process git@github.com:swoft-cloud/swoft-process.git 1.x --squash
