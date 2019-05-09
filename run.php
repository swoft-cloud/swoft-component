<?php

use Swoole\Event;

go(function () {
    try {
        global $argc, $argv;

        $phpunit = '';
        if(file_exists('../../bin/phpunit')){
            $phpunit = '../../bin/phpunit';
        }elseif (file_exists(__DIR__.'/vendor/bin/phpunit')){
            $phpunit = __DIR__.'/vendor/bin/phpunit';
        }

        if(empty($phpunit)){
            throw new \Exception('phpunit is not exist!');
        }

        require $phpunit;
    } catch (Throwable $e) {
        printf('%s At file=%s line=%d' . PHP_EOL,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine());
    }
});

Event::wait();

