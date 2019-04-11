<?php declare(strict_types=1);


namespace SwoftTest\Redis;


use Swoft\SwoftComponent;

class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
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
}