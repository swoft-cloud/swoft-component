<?php

namespace Swoft\Aop\Bootstrap\Boots;

use Swoft\Aop\Bean\Annotation;
use Swoft\Aop\Bean\Collector;
use Swoft\Aop\Bean\Parser;
use Swoft\Aop\Bean\Wrapper;
use Swoft\Aop\Proxy;
use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;


/**
 * Namespace compatibility with previous versions, which non-componentization version
 * @Bootstrap(order=1)
 */
class CompPreviousVersionNamespace implements Bootable
{

    /**
     * @return void
     */
    public function bootstrap()
    {
        $map = [
            Proxy\Proxy::class                      => 'Swoft\Proxy\Proxy',
            Wrapper\AspectWrapper::class            => 'Swoft\Bean\Wrapper',
            Parser\AfterParser::class               => 'Swoft\Bean\Parser\AfterParser',
            Parser\AfterReturningParser::class      => 'Swoft\Bean\Parser\AfterReturningParser',
            Parser\AfterThrowingParser::class       => 'Swoft\Bean\Parser\AfterThrowingParser',
            Parser\ArroundParser::class             => 'Swoft\Bean\Parser\ArroundParser',
            Parser\AspectParser::class              => 'Swoft\Bean\Parser\AspectParser',
            Parser\BeforeParser::class              => 'Swoft\Bean\Parser\BeforeParser',
            Parser\PointAnnotationParser::class     => 'Swoft\Bean\Parser\PointAnnotationParser',
            Parser\PointBeanParser::class           => 'Swoft\Bean\Parser\PointBeanParser',
            Parser\PointExecutionParser::class      => 'Swoft\Bean\Parser\PointExecutionParser',
            Collector\AspectCollector::class        => 'Swoft\Bean\Collector\AspectCollector',
            Annotation\AfterParser::class           => 'Swoft\Bean\Parser\After',
            Annotation\AfterReturningParser::class  => 'Swoft\Bean\Parser\AfterReturning',
            Annotation\AfterThrowingParser::class   => 'Swoft\Bean\Parser\AfterThrowing',
            Annotation\ArroundParser::class         => 'Swoft\Bean\Parser\Arround',
            Annotation\AspectParser::class          => 'Swoft\Bean\Parser\Aspect',
            Annotation\BeforeParser::class          => 'Swoft\Bean\Parser\Before',
            Annotation\PointAnnotationParser::class => 'Swoft\Bean\Parser\PointAnnotation',
            Annotation\PointBeanParser::class       => 'Swoft\Bean\Parser\PointBean',
            Annotation\PointExecutionParser::class  => 'Swoft\Bean\Parser\PointExecution',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias);
        }
    }
}