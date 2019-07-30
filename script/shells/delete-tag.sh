#!/usr/bin/env bash

TAG=$1

# import common functions
source "$(dirname $0)/common-func.sh"

# update one
if [[ "$2" != "" ]]; then
    COMPONENTS=$@
else
    COMPONENTS=$(ls src/)
fi

# git push REMOTE :refs/tags/TAG
for LIB_NAME in ${COMPONENTS} ; do
    colored_text "\n====== Delete remote tag for component:【${LIB_NAME}】" cyan

    echo "> git push ${LIB_NAME} :refs/tags/${TAG}"
    git push ${LIB_NAME} :refs/tags/${TAG}
done

colored_text "\nRelease Completed!"
