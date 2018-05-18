<?php

namespace Swoft\Aop\Bootstrap\Boots;

use Swoft\Aop\Bean\Annotation;
use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;


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
            Annotation\After::class => 'After',
            Annotation\AfterReturning::class => 'AfterReturning',
            Annotation\AfterThrowing::class => 'AfterThrowing',
            Annotation\Around::class => 'Around',
            Annotation\Aspect::class => 'Aspect',
            Annotation\Before::class => 'Before',
            Annotation\PointAnnotation::class => 'PointAnnotation',
            Annotation\PointBean::class => 'PointBean',
            Annotation\PointExecution::class => 'PointExecution',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}