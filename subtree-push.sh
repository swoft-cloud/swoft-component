#!/bin/sh

git pull --no-edit
echo 'unavailable!'
exit
git subtree push --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline.git master --squash
git subtree push --prefix=src/view git@github.com:swoft-cloud/swoft-view.git master --squash
git subtree push --prefix=src/http-server git@github.com:swoft-cloud/swoft-http-server.git master --squash
git subtree push --prefix=src/websocket-server git@github.com:swoft-cloud/swoft-websocket-server.git master --squash
git subtree push --prefix=src/task git@github.com:swoft-cloud/swoft-task.git master --squash
git subtree push --prefix=src/session git@github.com:swoft-cloud/swoft-session.git master --squash
git subtree push --prefix=src/service-governance git@github.com:swoft-cloud/swoft-service-governance.git master --squash
git subtree push --prefix=src/rpc-server git@github.com:swoft-cloud/swoft-rpc-server.git master --squash
git subtree push --prefix=src/rpc-client git@github.com:swoft-cloud/swoft-rpc-client.git master --squash
git subtree push --prefix=src/rpc git@github.com:swoft-cloud/swoft-rpc.git master --squash
git subtree push --prefix=src/redis git@github.com:swoft-cloud/swoft-redis.git master --squash
git subtree push --prefix=src/process git@github.com:swoft-cloud/swoft-process.git master --squash
git subtree push --prefix=src/memory git@github.com:swoft-cloud/swoft-memory.git master --squash
git subtree push --prefix=src/i18n git@github.com:swoft-cloud/swoft-i18n.git master --squash
git subtree push --prefix=src/http-client git@github.com:swoft-cloud/swoft-http-client.git master --squash
git subtree push --prefix=src/http-message git@github.com:swoft-cloud/swoft-http-message.git master --squash
git subtree push --prefix=src/devtool git@github.com:swoft-cloud/swoft-devtool.git master --squash
git subtree push --prefix=src/db git@github.com:swoft-cloud/swoft-db.git master --squash
git subtree push --prefix=src/data-parser git@github.com:swoft-cloud/swoft-data-parser.git master --squash
git subtree push --prefix=src/console git@github.com:swoft-cloud/swoft-console.git master --squash
git subtree push --prefix=src/cache git@github.com:swoft-cloud/swoft-cache.git master --squash
git subtree push --prefix=src/trace git@github.com:swoft-cloud/swoft-trace.git master --squash
git subtree push --prefix=src/queue git@github.com:swoft-cloud/swoft-queue.git master --squash
git subtree push --prefix=src/whoops git@github.com:swoft-cloud/swoft-whoops.git master --squash
git subtree push --prefix=src/log git@github.com:swoft-cloud/swoft-log.git master --squash
git subtree push --prefix=src/auth git@github.com:swoft-cloud/swoft-auth.git master --squash
git subtree push --prefix=src/swagger git@github.com:swoft-cloud/swoft-swagger.git master --squash
git subtree push --prefix=src/framework git@github.com:swoft-cloud/swoft-framework.git master --squash
git subtree push --prefix=src/process git@github.com:swoft-cloud/swoft-process.git master --squash
