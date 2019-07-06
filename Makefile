##There are some make command for the project
##

TAG=$(tag)

help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | sed -e 's/: / /'

##Available Commands:

  addrmt:	## Add the remote repository address of each component to the local remote
addrmt:
	./script/add-remotes.sh all

  spush:	## Push all update to remote sub-repo by git subtree push
spush:
	./script/subtree-push.sh all

  release:	## Release all sub-repo to new tag version and push to remote repo
release:
	./script/release-tag.sh -a -y -t $(TAG)

  sami:		## Gen classes docs by sami.phar
classdoc:
# rm -rf docs/classes-docs
	rm -rf docs/classes-docs
# gen docs
	php sami.phar update ./script/sami.doc.inc

  all:		## Run addrmt and spush and release
all: addrmt spush release

