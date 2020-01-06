# Swoft Component

[![Actions Status](https://github.com/swoft-cloud/swoft-component/workflows/Unit-tests/badge.svg)](https://github.com/swoft-cloud/swoft-component/actions)
[![Build Status](https://travis-ci.org/swoft-cloud/swoft-component.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft-component)


This repository is used to manage all swoft core components.

## [中文说明](README.zh-CN.md)

## IMPORTANT

All components will **NOT** be modified in the original repository of ext component, **SHOULD ALWAYS** be modified in this repository, also commit and push to this repository, and then @swoft-bot would sync changes to the original repository of component by `git subtree push`, notice that this action needs triggered by the repositories owner.

## Usage

Add require to `composer.json`

```json
"require": {
    "swoft/ext": "dev-master as 2.0"
}
```

Install:

```bash
composer update
```

### Unit Tests

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
./phpunit.sh event
// use run.php
php run.php -c src/event/phpunit.xml
// filter test method name
php run.php -c src/event/phpunit.xml --filter testAddModule
```

Output coverage data:

```bash
// output coverage. require xdebug ext
phpunit --coverage-text
// output coverage without xdebug
phpdbg -dauto_globals_jit=Off -qrr /usr/local/bin/phpunit --coverage-text
phpdbg -dauto_globals_jit=Off -qrr run.php --coverage-text -c src/event/phpunit.xml
```

## Releases

Please see https://github.com/swoftlabs/swoft-releasecli

## Document

- [中文文档](https://www.swoft.org/docs)
- [English](https://en.swoft.org/docs)

## Discuss

- Forum https://github.com/swoft-cloud/forum/issues
- Gitter.im https://gitter.im/swoft-cloud/community
- Reddit https://www.reddit.com/r/swoft/
- QQ Group1: 548173319      
- QQ Group2: 778656850

## Contributing

The development team welcomes you to submit PR (_Pull Request_) to us, but to ensure code quality and uniform style, go to the official main repository [swoft/swoft](https://github.com/swoft-cloud/swoft) and Development repository, Note the code and commit format when contributing code

### Precautions when initiating PR

- Please do not submit PR to each sub-repository, they are all read-only
- The _development repository_ for the core components is **[swoft/swoft-component][core]**
- The _development repository_ for extension components is **[swoft/swoft-ext][ext]**
- Please `fork` the corresponding development warehouse. After modification, please submit your PR to the corresponding development warehouse.

> Officially syncs code to individual sub-warehouses when new versions are released
### Commit Message

- the commit message can only be in English
- Please try to ensure that the commit message is meaningful
- it is best to start with the keyword `add:` `update:` `fix:`

### Code Style

- Submitted PHP code **Must** Follow PSR-2 code style
- Reasonable and meaningful class, method, variable naming
- Appropriate comments, reasonable use of blank lines to keep the code simple and easy to read
- Don't include some meaningless information such as `@author`, etc. (_author is  that can be seen from the commit log_)


[core]: https://github.com/swoft-cloud/swoft-component
[ext]: https://github.com/swoft-cloud/swoft-ext
