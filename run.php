<?php
go(function () {
    try {
        global $argc, $argv;
        require '../../bin/phpunit';
    } catch (Throwable $e) {
            var_dump($e->getMessage());
    }
});

\Swoole\Event::wait();