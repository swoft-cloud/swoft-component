<?php

namespace Swoft;

/**
 * Swoft is a helper class serving common framework functions.
 *
 * @since 2.0
 */
class Swoft
{
    /**
     * Aliases
     *
     * @var array
     */
    private static $aliases = [
        '@app'     => APP_PATH,
        '@base'    => BASE_PATH,
        '@config'  => CONFIG_PATH,
        '@runtime' => RUNTIME_PATH
    ];

    /**
     * Register multi aliase
     *
     * @param array $aliases
     */
    public static function setAliases(array $aliases): void
    {
        foreach ($aliases as $name => $path) {
            self::setAlias($name, $path);
        }
    }

    /**
     * Register alias
     *
     * @param string $alias
     * @param string $path
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function setAlias(string $alias, string $path = null): void
    {
        if ($alias[0] !== '@') {
            $alias = '@' . $alias;
        }

        // Delete alias
        if (!$path) {
            unset(self::$aliases[$alias]);

            return;
        }

        // $path is not alias
        if ($path[0] !== '@') {
            self::$aliases[$alias] = $path;

            return;
        }

        // $path is alias
        if (isset(self::$aliases[$path])) {
            self::$aliases[$alias] = self::$aliases[$path];

            return;
        }

        list($root) = explode('/', $path);
        if (!isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException('The set root alias does not exist，alias=' . $root);
        }

        $rootPath  = self::$aliases[$root];
        $aliasPath = str_replace($root, '', $path);

        self::$aliases[$alias] = $rootPath . $aliasPath;
    }

    /**
     * Get alias
     *
     * @param string $alias
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getAlias(string $alias): string
    {
        // empty OR not an alias
        if (!$alias || $alias[0] !== '@') {
            return $alias;
        }

        if (isset(self::$aliases[$alias])) {
            return self::$aliases[$alias];
        }

        list($root) = \explode('/', $alias, 2);
        if (!isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException('The set root alias does not exist，alias=' . $root);
        }

        $rootPath  = self::$aliases[$root];
        $aliasPath = \str_replace($root, '', $alias);

        return $rootPath . $aliasPath;
    }

    /**
     * Whether the aliase is exist
     *
     * @param string $alias
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function hasAlias(string $alias): bool
    {
        // empty OR not an alias
        if (!$alias || $alias[0] !== '@') {
            return false;
        }

        return isset(self::$aliases[$alias]);
    }
}