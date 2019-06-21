#!/usr/bin/env bash

set -e

REPOS=$(ls src/)
TARGET_BRANCH=master
echo "Will pushed projects:"
echo ${REPOS}

echo "Update code to latest"
echo "> git pull --no-edit"
git pull --no-edit

# git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
for lbName in ${REPOS} ; do
    echo "======> Push the project:【${lbName}】"
    echo "> git subtree push --prefix=src/${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git ${TARGET_BRANCH} --squash"
done

echo "Push Completed!"
