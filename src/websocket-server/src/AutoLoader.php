<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 12:47
 */

namespace Swoft\WebSocket\Server;

use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 * @package Swoft\WebSocket\Server\Event
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return false;
    }

    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array
    {
        return [__NAMESPACE__ => __DIR__];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        // TODO: Implement metadata() method.
    }

    public function coreBean(): array
    {
        return [
            'wsDispatcher' => [
                'class' => Dispatcher::class,
            ],
            'wsRouter'     => [
                'class' => HandlerMapping::class,
            ],
        ];
    }
}