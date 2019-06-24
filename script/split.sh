#!/bin/sh

git subtree split -P ../src/${1} -b ${1} \
  && cd /tmp/ \
  && mkdir ${1} \
  && cd ${1} \
  && git init \
  && git remote add origin git@github.com:swoft-cloud/swoft-${1}.git \
  && git pull /data/www/swoft/component/ ${1} \
  && git push origin -f master
