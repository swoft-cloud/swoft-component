<?php

use Swoole\Event;

go(function () {
    try {
        global $argc, $argv;
        require '../../bin/phpunit';
    } catch (Throwable $e) {
        printf(
            '%s At file=%s line=%d' . PHP_EOL,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }
});

Event::wait();
