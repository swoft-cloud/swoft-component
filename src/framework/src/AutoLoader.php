<?php

namespace Swoft;

use Swoft\Config\Config;
use Swoft\Annotation\AutoLoader as AnnotationAutoLoader;
use Swoft\Event\Manager\EventManager;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends AnnotationAutoLoader implements DefinitionInterface
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
            ],
            'eventManager' => [
                'class' => EventManager::class,
            ],
        ];
    }
}