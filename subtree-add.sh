#!/bin/sh
#
# add repo to the component
# usage: ./subtree-add.sh http-server
#

binName="bash $(basename $0)"

if [[ -z "$1" ]]; then
    echo -e "Usage: $binName PROJECT_NAME\n"
    echo "Example:"
    echo "  $binName http-server"
    exit
fi

REPO=$1

set -ex

# run command
git subtree add --prefix=src/${REPO} git@github.com:swoft-cloud/swoft-${REPO}.git master --squash
