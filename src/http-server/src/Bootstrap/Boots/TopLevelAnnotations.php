<?php

namespace Swoft\Http\Server\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\Http\Server\Bean\Annotation;
use Swoft\Http\Message\Bean\Annotation as HttpMessageAnnotation;


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
            // Current Component
            Annotation\Controller::class     => 'Controller',
            Annotation\RequestMapping::class => 'RequestMapping',
            Annotation\RequestMethod::class  => 'RequestMethod',
            // HTTP Message Component
            HttpMessageAnnotation\Middleware::class => 'Middleware',
            HttpMessageAnnotation\Middlewares::class => 'Middlewares',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}