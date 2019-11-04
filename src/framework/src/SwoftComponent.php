<?php declare(strict_types=1);

namespace Swoft;

use Swoft\Contract\ComponentInterface;
use function array_merge;

/**
 * Class SwoftComponent
 *
 * @since 2.0
 */
abstract class SwoftComponent implements ComponentInterface
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->metadata = array_merge(self::DEFAULT_META, $this->metadata());
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return true;
    }

    /**
     * @return bool
     * @deprecated will deleted since 2.0.8
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * Bean definitions of the component
     *
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
    abstract protected function metadata(): array;

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
