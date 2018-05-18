<?php

namespace Swoft\Db\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\Db\Bean\Annotation;


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
            Annotation\Column::class => 'Column',
            Annotation\Connection::class => 'Connection',
            Annotation\Entity::class => 'Entity',
            Annotation\Id::class => 'Id',
            Annotation\Required::class => 'Required',
            Annotation\Statement::class => 'Statement',
            Annotation\Table::class => 'Table',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}