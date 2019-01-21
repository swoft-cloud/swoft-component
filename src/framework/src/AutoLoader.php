<?php

namespace Swoft;


use Swoft\Config\Config;
use Swoft\Config\Parser\PhpParser;

class AutoLoader extends \Swoft\Annotation\AutoLoader implements DefinitionInterface
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Core bean definition
     *
     * @return array
     */
    public function coreBean(): array
    {
        return [
            'config' => [
                'class'   => Config::class,
                'path'    => alias('@config'),
                'parsers' => [
                    Config::TYPE_PHP => '${phpParser}'
                ]
            ]
        ];
    }
}