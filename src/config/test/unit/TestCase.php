<?php declare(strict_types=1);


namespace SwoftTest\Config\Unit;

use Swoft\Config\Config;

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
            'key'   => [
                'key2' => 'value2'
            ],
            'key2'  => 'value2',
            'db'    => [
                'host'     => '127.0.0.1',
                'user'     => 'db',
                'password' => 'password',
            ],
            'redis' => [
                'host'     => '127.0.0.1',
                'user'     => 'redis',
                'password' => 'password',
            ],
            'user'  => [
                'sms'    => [
                    'monitor' => '135xxx',
                ],
                'member' => [
                    'score' => [
                        'one' => 12,
                    ]
                ]
            ]
        ];
    }
}
