<?php

namespace Swoft\Stdlib\Helper;

use Composer\Autoload\ClassLoader;

/**
 * Class ComposerHelper
 *
 * @since 2.0
 */
class ComposerHelper
{
    /**
     * Get composer class loader
     *
     * @return ClassLoader
     * @throws \RuntimeException
     */
    public static function getClassLoader(): ClassLoader
    {
        $autoloadFunctions = \spl_autoload_functions();

        foreach ($autoloadFunctions as $autoloader) {
            if (\is_array($autoloader) && isset($autoloader[0]) && \is_object($autoloader[0])) {
                if ($autoloader[0] instanceof ClassLoader) {
                    return $autoloader[0];
                }
            }
        }

        throw new \RuntimeException('Composer ClassLoader not found!');
    }
}