#!/usr/bin/env bash

set -e

# import common functions
source "$(dirname $0)/common-func.sh"

binName="bash $(basename $0)"

if [[ -z "$1" ]]
then
    echo "Push all update to remote sub-repo by git subtree push"
    echo -e "Usage:\n  $binName NAME(S)"
    echo "Example:"
    echo "  $binName http-server"
    echo "  $binName http-server http-message"
    exit 0
fi

# update one
if [[ "$1" != "all" ]]; then
    COMPONENTS=$@
else
    COMPONENTS=$(ls src/)
fi

TARGET_BRANCH=master

echo "Update code to latest"
echo "> git pull --no-edit"
git pull --no-edit

echo "Will pushed projects:"
echo ${COMPONENTS}

# git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
# git subtree push --prefix=src/stdlib stdlib master
for lbName in ${COMPONENTS} ; do
    colored_text "\n======> Push the project:【${lbName}】"
#    yellow_text "> git subtree pull --prefix=src/${lbName} ${lbName} ${TARGET_BRANCH} --squash"
#    git subtree pull --prefix=src/${lbName} ${lbName} ${TARGET_BRANCH} --squash

    yellow_text "> git subtree push --prefix=src/${lbName} ${lbName} ${TARGET_BRANCH}"
    git subtree push --prefix=src/${lbName} ${lbName} ${TARGET_BRANCH} --squash
done

colored_text "\nPush Completed!"
