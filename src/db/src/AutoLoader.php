<?php declare(strict_types=1);


namespace Swoft\Db;


class AutoLoader
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
                'dsn'      => '',
                'username' => '',
                'password' => '',
            ],
            'db.pool' => [
                'class' => Pool::class,
                'db'    => bean('db')
            ]
        ];
    }
}