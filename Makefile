# ref https://gist.github.com/inhere/c98df2b096ee3ccc3d36ec61923c9fc9
.DEFAULT_GOAL := help
.PHONY: all usage help clean

##There are some make command for the project
##

TAG=$(tag)

help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | sed -e 's/: / /'

##Available Commands:

  addrmt:	## Add the remote repository address of each component to the local remote
addrmt:
	php dtool.php git:addrmt --all

  spull:	## Push all update to remote sub-repo by git subtree push
spull:
	php dtool.php git:spush --all

  spush:	## Push all update to remote sub-repo by git subtree push
spush:
	php dtool.php git:spush --all

  release:	## Release all sub-repo to new tag version and push to remote repo. eg: tag=v2.0.3
release:
	php dtool.php tag:release --all -y -t $(TAG)

  sami:		## Gen classes docs by sami.phar
classdoc:
# rm -rf docs/classes-docs
	rm -rf docs/classes-docs
# gen docs
	php sami.phar update ./script/sami.doc.inc

  all:		## Run addrmt and spush and release
all: addrmt spush release

