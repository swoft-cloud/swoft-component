#!/usr/bin/env bash

#set -e

# import common functions
source "$(dirname $0)/common-func.sh"

binName="bash $(basename $0)"

if [[ -z "$1" ]]
then
    echo "Add the remote repository address of each component to the local remote"
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

REMOTE_PREFIX='git@github.com:swoft-cloud/swoft-'

echo "Will added components:"
echo " " ${COMPONENTS}

# git remote add stdlib http://github.com/swoft-cloud/swoft-stdlib.git
# git remote add stdlib git@github.com:swoft-cloud/swoft-stdlib.git
# git subtree add --prefix=src/stdlib stdlib master
# git subtree pull --prefix=src/stdlib stdlib master
# git subtree push --prefix=src/stdlib stdlib master
for lbName in ${COMPONENTS} ; do
    colored_text "\n---- Check sub-component remote: ${lbName}"
    yellow_text "> git remote -v | grep swoft-${lbName}"
    REMOTE_INFO=`git remote -v | grep swoft-${lbName}`

    if [[ -n "$REMOTE_INFO" ]]; then
        colored_text "${lbName}: has been add remote, skip add"
        continue
    fi

    cyan_text "\n======> Add the project:【${lbName}】"
    yellow_text "> git remote add ${lbName} ${REMOTE_PREFIX}${lbName}.git"
    git remote add ${lbName} ${REMOTE_PREFIX}${lbName}.git
done

colored_text "\nAdd Remote Completed!"
