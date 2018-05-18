<?php

namespace Swoft\View\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\View\Bean\Annotation;


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
            Annotation\View::class => 'View',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}