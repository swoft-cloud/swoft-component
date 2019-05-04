# Swoft Component

This repository is used to manage all swoft components.

# IMPORTANT

All components will **NOT** be modified in the original repository of component, **SHOULD ALWAYS** be modified in this repository, also commit and push to this repository, and then @swoft-bot would sync changes to the original repository of component by `git subtree push`, notice that this action needs triggered by the repositories owner.

## Usage

### Add a Sub Repository

```bash
git subtree add --prefix=src/[folder] [repository] [ref] --squash
```

> Note that `--squash` option is required.

e.g. Add [swoft/config](https://github.com/swoft-cloud/swoft-config) component as a Sub Repository,

```php
git subtree add --prefix=src/config git@github.com:swoft-cloud/swoft-config master --squash
```

### Commit changes

Just use `git commit` as usual, and Push to this repository

### Sync changes to the Original Repository of Component

```bash
git subtree push --prefix=src/[folder] [repository] [ref] --squash
```

> Note that `--squash` option is required.

e.g. Add [swoft/config](https://github.com/swoft-cloud/swoft-config) component as a Sub Repository

```bash
git subtree push --prefix=src/config git@github.com:swoft-cloud/swoft-config master --squash
```

> Tips:
> You could use `remote` to instead of `[repository]` property for easier to use.
> e.g. Add `Remote` first, `git remote add -f config git@github.com:swoft-cloud/swoft-config.git`,
> after this, you could use `config` instead of `[repository]`,
> for example `git subtree push --prefix=src/config config master --squash`

### Release a new version of component

After `Sync changes to the Original Repository of Component`, you just need to Release a new version in the original repository of component.

### Pull changes from the Original Repository of Component

We do **NOT** suggest modifying code in the original repository, but if you do, you could use the command below to merge it.

```bash
git subtree pull --prefix=src/[folder] [repository] [ref] --squash
```

> Note that `--squash` option is required.

e.g. Pull [swoft/config](https://github.com/swoft-cloud/swoft-config) repository into `src/config`

```bash
git subtree pull --prefix=src/config git@github.com:swoft-cloud/swoft-config master --squash
```

## Unit Tests

Quick run tests for component:

```bash
// For all components
./phpunit.sh all
// For multi components
./phpunit.sh db event
// For one component
./phpunit.sh event
```

Only tests an special component:

```bash
php run.php -c src/event/phpunit.xml
php run.php -c src/event/phpunit.xml --filter testAddModule
```

Output coverage data(TODO):

```bash
// output coverage. require xdebug ext
phpunit --coverage-text

// output coverage without xdebug
phpdbg -dauto_globals_jit=Off -qrr /usr/local/bin/phpunit --coverage-text
phpdbg -dauto_globals_jit=Off -qrr run.php --coverage-text -c src/event/phpunit.xml
```
