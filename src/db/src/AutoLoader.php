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
    public function beans(): array
    {
        return [
            'db'      => [
                'class'    => Database::class,
                'dsn'      => 'mysql:dbname=dbname;host=127.0.0.1',
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
