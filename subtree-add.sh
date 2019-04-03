#!/bin/sh
#
# add repo to the component
#

REPO=$@

set -ex

# run command
git subtree add --prefix=src/${REPO} git@github.com:swoft-cloud/swoft-${REPO}.git 2.0 --squash
