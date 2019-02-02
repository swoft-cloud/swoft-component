<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 17:32
 */

namespace Swoft;

use Swoft\Contract\ComponentInterface;

/**
 * Class SwoftComponent
 * @package Swoft
 */
abstract class SwoftComponent implements ComponentInterface
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $defaultData = [
            'name'        => '',
            'title'       => '',
            'description' => '',
        ];

        $this->metadata = \array_merge($defaultData, $this->metadata());
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function coreBean(): array
    {
        return [];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    abstract public function metadata(): array;

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
