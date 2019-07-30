dist: xenial
language: php

php:
  - '7.1'
  - '7.2'
  - '7.3'

install:
  - wget https://github.com/swoole/swoole-src/archive/{{SWOOLE_VERSION}}.tar.gz -O swoole.tar.gz && mkdir -p swoole && tar -xf swoole.tar.gz -C swoole --strip-components=1 && rm swoole.tar.gz && cd swoole && phpize && ./configure && make -j$(nproc) && make install && cd -
  - echo "extension = swoole.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini

before_script:
  - composer config -g process-timeout 900 && composer update
  - phpenv config-rm xdebug.ini
#  - composer require php-coveralls/php-coveralls:^2.1.0

script:
  - composer test
#  - php vendor/bin/phpunit --coverage-clover clover.xml

#after_success:
#    - vendor/bin/php-coveralls --coverage_clover=clover.xml --json_path=coveralls-upload.json -v
