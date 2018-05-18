<?php

namespace Swoft\Sg\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\Sg\Bean\Annotation;


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
            Annotation\Breaker::class => 'Breaker',
            Annotation\Fallback::class => 'Fallback',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}