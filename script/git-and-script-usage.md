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

```bash
bash ./script/subtree-push.sh -a -t v2.0.4
```

```bash
bash ./script/release-tag.sh -a -t v2.0.4
```
