<?php

namespace Swoft\Helper;

/**
 * Class EnvHelper
 *
 * @package Swoft\Helper
 */
class EnvHelper
{
    /**
     * @throws \RuntimeException
     */
    public static function check()
    {
        if (!PhpHelper::isCli()) {
            throw new \RuntimeException('Server must run in the CLI mode.');
        }

        if (! version_compare(PHP_VERSION, '7.0')) {
            throw new \RuntimeException('PHP version has to greater or equal to 7.0, 7.1 is recommended !');
        }

        if (!\extension_loaded('swoole') && SWOOLE_VERSION >= '2.1.0') {
            throw new \RuntimeException("Version of Swoole extension has to greater or equal to 2.1 and enable coroutine  feature by build parameter '--enable-coroutine' !");
        }

        if (!class_exists('Swoole\Coroutine')) {
            throw new \RuntimeException("Swoole extention has to enable coroutine feature by build parameter '--enable-coroutine' !");
        }

        if (\extension_loaded('blackfire')) {
            throw new \RuntimeException('Blackfire extention is incompatible with Swoole 2, please disable it !');
        }

        if (\extension_loaded('xdebug')) {
            throw new \RuntimeException('Xdebug extention is incompatible with Swoole 2, please disable it !');
        }

        if (\extension_loaded('uopz') && !ini_get('uopz.disable')) {
            throw new \RuntimeException('Uopz extention is incompatible with Swoole 2, please disable it !');
        }

        if (\extension_loaded('xhprof')) {
            throw new \RuntimeException('Xhprof extention is incompatible with Swoole 2, please disable it !');
        }

        if (\extension_loaded('zend')) {
            throw new \RuntimeException('Zend extention is incompatible with Swoole 2, please disable it !');
        }
    }
}
