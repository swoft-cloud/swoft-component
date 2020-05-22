<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Config\Unit;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function getConfigData(): array
    {
        return [
            'key'  => [
                'key2' => 'value2Pro',
                'key3' => 'value2'
            ],
            'key2' => 'value2',
            'key3' => 'value3Pro',
            'data' => [
                'key'  => 'value2Pro',
                'key2' => 'value2ProKey2',
                'key3' => [
                    'key1' => [
                        'key' => 'dataChildKey1'
                    ]
                ]
            ]
        ];
    }
}
