<?php declare(strict_types=1);


namespace Swoft\Db;


use Swoft\SwoftComponent;

class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function coreBean(): array
    {
        return [
            'db'      => [
                'class'    => Database::class,
                'dsn'      => 'mysql:dbname=test;host=172.17.0.4',
                'username' => 'swoft',
                'password' => 'swoft123456',
            ],
            'db.pool' => [
                'class'    => Pool::class,
                'database' => \bean('db')
            ]
        ];
    }

    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }
}