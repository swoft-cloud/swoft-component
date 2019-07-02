<?php declare(strict_types=1);

namespace Swoft\Helper;

use RuntimeException;
use function extension_loaded;
use function implode;
use function version_compare;
use const PHP_VERSION;
use const SWOOLE_VERSION;

/**
 * Class SwoftHelper
 *
 * @since 2.0
 */
class SwoftHelper
{
    /**
     * @param array $stats
     *
     * @return string
     */
    public static function formatStats(array $stats): string
    {
        $strings = [];

        foreach ($stats as $name => $count) {
            $strings[] = "$name $count";
        }

        return implode(', ', $strings);
    }

    /**
     * Check runtime extension conflict
     *
     * @param string $minPhp
     * @param string $minSwoole
     */
    public static function checkRuntime(string $minPhp = '7.1', string $minSwoole = '4.3.0'): void
    {
        // if (!EnvHelper::isCli()) {
        //     throw new RuntimeException('Server must run in the CLI mode.');
        // }

        if (version_compare(PHP_VERSION, $minPhp, '<')) {
            throw new RuntimeException('Run the server requires PHP version > 7.1! current is ' . PHP_VERSION);
        }

        if (!extension_loaded('swoole')) {
            throw new RuntimeException("Run the server, extension 'swoole' is required!");
        }

        if (version_compare(SWOOLE_VERSION, $minSwoole, '<')) {
            throw new RuntimeException('Run the server requires swoole version > 4.3.0! current is ' . SWOOLE_VERSION);
        }

        $conflicts = [
            'blackfire',
            'xdebug',
            'uopz',
            'xhprof',
            'zend',
            'trace',
        ];

        foreach ($conflicts as $ext) {
            if (extension_loaded($ext)) {
                throw new RuntimeException("The extension of '{$ext}' must be closed, otherwise swoft will be affected!");
            }
        }

        // if (extension_loaded('uopz') && !ini_get('uopz.disable')) {
        //     throw new RuntimeException("The extension of 'uopz' must be closed, otherwise swoft will be affected!");
        // }
    }
}
