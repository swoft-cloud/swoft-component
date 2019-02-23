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

    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }
}