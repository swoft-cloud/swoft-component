#!/bin/sh

# 重新split出一个新起点（这样，每次提交subtree的时候就不会从头遍历一遍了）
# git subtree split --rejoin --prefix=components/zenjs --branch new_zenjs
git subtree split -P ../src/${1} -b ${1} \
  && cd /tmp/ \
  && mkdir ${1} \
  && cd ${1} \
  && git init \
  && git remote add origin git@github.com:swoft-cloud/swoft-${1}.git \
  && git pull /data/www/swoft/component/ ${1} \
  && git push origin -f master
