#!/usr/bin/env bash

set -e

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
    components=$@
else
    components=$(ls src/)
fi

# git remote add stdlib http://github.com/swoft-cloud/swoft-stdlib.git
# git remote add stdlib git@github.com:swoft-cloud/swoft-stdlib.git
# git subtree add --prefix=src/stdlib stdlib master
# git subtree pull --prefix=src/stdlib stdlib master
# git subtree push --prefix=src/stdlib stdlib master
for lbName in ${components} ; do
    echo ""
    echo "======> Add the project:【${lbName}】"
    echo "> git remote add ${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git"
    git remote add ${lbName} git@github.com:swoft-cloud/swoft-${lbName}.git
done

echo ""
echo "Add Remote Completed!"
