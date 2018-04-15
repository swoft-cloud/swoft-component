# Swoft Component

This reposiory is used to manage all swoft components.  

# IMPORTANT

All components will **NOT** be modified in the original repository of component, **SHOULD ALWAYS** be modified in this repository, also commit and push to this repository, and use `git subtree push` to sync changes to the original repository of component.

## Usage

### Add an Sub Repository

```bash
git subtree add --prefix=src/[folder] [repository] [ref] --squash
```

> Note that `--squash` option is required.

e.g. Add [swoft/pipeline](https://github.com/swoft-cloud/swoft-pipeline) component as an Sub Repository, 

```php
git subtree add --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline master --squash
```

### Commit changes

Just use `git commit` as usual, and Push to this repository

### Sync changes to the Original Repository of Component

```bash
git subtree push --prefix=src/[folder] [repository] [ref] --squash
```

> Note that `--squash` option is required.

e.g. Add [swoft/pipeline](https://github.com/swoft-cloud/swoft-pipeline) component as an Sub Repository

```bash
git subtree push --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline master --squash
```

> Tips:
> You could use `remote` to instead of `[repository]` property for easier to use.  
> e.g. Add `Remote` first, `git remote add -f pipeline git@github.com:swoft-cloud/swoft-pipeline.git`,  
> after this, you could use `pipeline` instead of `[repository]`,  
> for example `git subtree push --prefix=src/pipeline pipeline master --squash`

### Release a new version of component

After `Sync changes to the Original Repository of Component`, you just need to Release a new version in the original repository of component.

### Pull changes from original repository

We do **NOT** suggest modify code in the original repository, but if you do, you could use `git subtree pull --prefix=src/[folder] [repository] [ref] --squash` command to merge it.

> Note that `--squash` option is required.

e.g. Pull [swoft/pipeline](https://github.com/swoft-cloud/swoft-pipeline) repository into `src/pipeline`

```bash
git subtree pull --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline master --squash
```
