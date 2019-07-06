# git and script usage

## prepare

```bash
bash ./script/add-remotes.sh
```

## git subtree

```bash
git remote add stdlib http://github.com/swoft-cloud/swoft-stdlib.git
git remote add stdlib git@github.com:swoft-cloud/swoft-stdlib.git

git subtree add --prefix=src/stdlib stdlib master
git subtree pull --prefix=src/stdlib stdlib master
# git subtree push --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
git subtree push --prefix=src/stdlib stdlib master
```

## release flow

- add git remote

```bash
bash ./script/add-remotes.sh all
```

- git subtree push

```bash
bash ./script/subtree-push.sh all
```

- release new tag

```bash
bash ./script/release-tag.sh -a -y -t v2.0.4
```

## use makefile

```bash
make # see help

# do add remote and push and release tag
make all tag=v2.0.4
```
