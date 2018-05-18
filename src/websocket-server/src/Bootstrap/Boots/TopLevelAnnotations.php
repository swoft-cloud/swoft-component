<?php

namespace Swoft\WebSocket\Server\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\WebSocket\Server\Bean\Annotation;


/**
 * Optimize namespace compatibility about Top level annotations
 * @Bootstrap(order=1)
 */
class TopLevelAnnotations implements Bootable
{

    /**
     * @return void
     */
    public function bootstrap()
    {
        $map = [
            Annotation\WebSocket::class => 'WebSocket',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}