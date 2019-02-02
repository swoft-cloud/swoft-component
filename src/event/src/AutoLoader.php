<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 17:59
 */

namespace Swoft\Event;

use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 * @package Swoft\Event
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Metadata information for the component
     *
     * @return array
     * [
     *  'name'        => 'my component',
     *  'author'      => 'tom',
     *  'version'     => '1.0.0',
     *  'createAt'    => '2019.02.12',
     *  'updateAt'    => '2019.04.12',
     *  'description' => 'description for the component',
     *  'homepage'    => 'https://github.com/inhere/some-component',
     * ]
     */
    public function getMetadata(): array
    {
        return [

        ];
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
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }
}