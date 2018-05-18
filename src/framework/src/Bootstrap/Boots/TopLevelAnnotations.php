<?php

namespace Swoft\Bootstrap\Boots;

use Swoft\Bean\Annotation;
use Swoft\Bean\Annotation\Bootstrap;


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
            Annotation\Bean::class => 'Bean',
            Annotation\BeforeStart::class => 'BeforeStart',
            Annotation\BootBean::class => 'BootBean',
            Annotation\Bootstrap::class => 'Bootstrap',
            Annotation\Cacheable::class => 'Cacheable',
            Annotation\CacheEvict::class => 'CacheEvict',
            Annotation\CachePut::class => 'CachePut',
            Annotation\Caching::class => 'Caching',
            Annotation\Definition::class => 'Definition',
            Annotation\Enum::class => 'Enum',
            Annotation\ExceptionHandler::class => 'ExceptionHandler',
            Annotation\Floats::class => 'Floats',
            Annotation\Handler::class => 'Handler',
            Annotation\Inject::class => 'Inject',
            Annotation\Integer::class => 'Integer',
            Annotation\Listener::class => 'Listener',
            Annotation\None::class => 'None',
            Annotation\Number::class => 'Number',
            Annotation\Pool::class => 'Pool',
            Annotation\Scope::class => 'Scope',
            Annotation\ServerListener::class => 'ServerListener',
            Annotation\Strings::class => 'Strings',
            Annotation\SwooleListener::class => 'SwooleListener',
            Annotation\ValidatorFrom::class => 'ValidatorFrom',
            Annotation\Value::class => 'Value',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}