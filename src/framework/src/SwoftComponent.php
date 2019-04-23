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
     * @var bool Enable the component
     */
    private $enable;

    /**
     * @var array
     */
    private $metadata;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->enable   = $this->enable();
        $this->metadata = \array_merge(self::DEFAULT_META, $this->metadata());
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
    public function beans(): array
    {
        return [];
    }

    /**
     * Metadata information for the component.
     *
     * Quick config:
     *
     * ```php
     * $jsonFile = \dirname(__DIR__) . '/composer.json';
     *
     * return ComposerJSON::open($jsonFile)->getMetadata();
     * ```
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

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }
}
