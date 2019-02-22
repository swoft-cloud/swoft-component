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
            if (\is_array($autoloader) && isset($autoloader[0])) {
                $composerLoader = $autoloader[0];

                if (\is_object($composerLoader) && $composerLoader instanceof ClassLoader) {
                    return $composerLoader;
                }
            }
        }

        throw new \RuntimeException('Composer ClassLoader not found!');
    }

    /**
     * @param string $file
     * @return array
     */
    public static function parseLockFile(string $file): array
    {
        if (!\is_file($file)) {
            return [];
        }

        if (!$json = \file_get_contents($file)) {
            return [];
        }

        /** @var array[] $data */
        $data = \json_decode($json, true);
        $components = [];

        if (!$data || !isset($data['packages'])) {
            return [];
        }

        foreach ($data['packages'] as $package) {
            if (0 !== \strpos($package['name'], 'swoft/')) {
                continue;
            }

            $components[] = [
                'name' => $package['name'],
                'version' => $package['version'],
                'source' => $package['source'],
                'require' => $package['require'] ?? [],
                'description' => $package['description'],
                'keywords' => $package['keywords'],
                'time' => $package['time'],
            ];
        }

        return $components;
    }
}
