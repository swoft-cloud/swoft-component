<?php declare(strict_types=1);

namespace Swoft\Helper;

use RuntimeException;
use function extension_loaded;
use function implode;
use function version_compare;

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
     * @throws RuntimeException
     */
    public static function checkRuntime(): void
    {
        // if (!EnvHelper::isCli()) {
        //     throw new RuntimeException('Server must run in the CLI mode.');
        // }

        if (!version_compare(PHP_VERSION, '7.1')) {
            throw new RuntimeException('Run the server requires PHP version > 7.1');
        }

        if (!extension_loaded('swoole')) {
            throw new RuntimeException("Run the server, extension 'swoole 4.3+' is required!");
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
