<?php

namespace Swoft\Annotation;

/**
 * Class loader interface
 */
interface LoaderInterface
{
    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array;

    /**
     * Disable or enable this component.
     * @return bool
     */
    // public function enable(): bool;

    /**
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
    // public function getMetadata(): array;
}