<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Contract;

use Swoft\Annotation\Contract\LoaderInterface;

/**
 * Interface ComponentInterface
 *
 * @since 2.0
 */
interface ComponentInterface extends DefinitionInterface, LoaderInterface
{
    public const DEFAULT_META = [
        'name'        => '',
        'title'       => '',
        'version'     => '',
        'homepage'    => '',
        'description' => '',
    ];

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * Metadata information for the component
     *
     * @return array
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
     */
    public function getMetadata(): array;
}
