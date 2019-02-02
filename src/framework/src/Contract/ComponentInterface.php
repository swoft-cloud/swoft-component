<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 17:31
 */

namespace Swoft\Contract;

use Swoft\Annotation\LoaderInterface;
use Swoft\DefinitionInterface;

/**
 * Interface ComponentInterface
 * @since 2.0
 * @package Swoft\Contract
 */
interface ComponentInterface extends DefinitionInterface, LoaderInterface
{
    /**
     * Disable or enable this component.
     *
     * @return bool
     */
    public function enable(): bool;

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
    public function getMetadata(): array;
}