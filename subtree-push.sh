#!/bin/sh

# git subtree split -P src/annotation -b annotation
#
#
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
# git remote add origin && git pull /Users/stelin/swoft/swoft/vendor/swoft/component/   && git push origin -f master
#
#
#

mkdir ${1} && cd ${1} && git init && git remote add origin git@github.com:swoft-cloud/swoft-${1}.git&& git pull /Users/stelin/swoft/swoft/vendor/swoft/component/  ${1} && git push origin -f master




git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
