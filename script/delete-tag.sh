#!/usr/bin/env bash

TAG=$1
COMPONENTS=$(ls src/)

# import common functions
source "$(dirname $0)/common-func.sh"

for LIB_NAME in ${COMPONENTS} ; do
    colored_text "\n====== Releasing the component:【${LIB_NAME}】" cyan

    echo "> git push ${LIB_NAME} :refs/tags/${TAG}"
    git push ${LIB_NAME} :refs/tags/${TAG}
done

colored_text "\nRelease Completed!"
