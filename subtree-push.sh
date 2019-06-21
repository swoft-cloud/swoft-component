#!/usr/bin/env bash

set -e

REPOS=$(ls src/)
TARGET_BRANCH=master

echo "Update code to latest"
echo "> git pull --no-edit"
git pull --no-edit

echo "Will pushed projects:"
echo ${REPOS}

# git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
for lbName in ${REPOS} ; do
    echo ""
    echo "======> Push the project:【${lbName}】"
    echo "> git subtree push --prefix=src/${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git ${TARGET_BRANCH} --squash"
    git subtree push --prefix=src/${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git ${TARGET_BRANCH} --squash
done

echo ""
echo "Push Completed!"
