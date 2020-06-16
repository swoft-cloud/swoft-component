<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     * Metadata information for the component.
     *
     * e.g:
     * [
     *  'name'        => 'user/package', // same composer.json -> name
     *  'title'       => 'my component',
     *  'version'     => '1.0.0',
     *  'authors'     => [
     *      [
     *          'name' => 'tom',
     *          'homepage' => 'https://github.com/tom'
     *      ]
     *  ],
     *  'keywords'    => ['one', 'two'],
     *  'createAt'    => '2019.02.12',
     *  'updateAt'    => '2019.04.12',
     *  'description' => 'description for the component',
     *  'homepage'    => 'https://github.com/inhere/some-component',
     * ]
     *
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
     * @return string
     */
    public function getClass(): string
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->metadata['name'] ?? '';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->metadata['version'] ?? '';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->metadata['description'] ?? '';
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getMetaValue(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
