#!/usr/bin/env bash
# TODO with release message

set -e

if (( "$1" != 1 ))
then
    echo "Tag has to be provided"
    exit 1
fi

./subtree-push.sh

RELEASE_TAG=$1
TARGET_BRANCH=master
REPOS=$(ls src/)

echo "Will released version: ${RELEASE_TAG}"
echo "Will released projects:"
echo ${REPOS}

# git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
for lbName in ${REPOS} ; do
    echo "======> Release the project:【${lbName}】"
    echo "> git subtree push --prefix=src/${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git ${TARGET_BRANCH} --squash"
done

echo ""
echo "Completed!"
exit
