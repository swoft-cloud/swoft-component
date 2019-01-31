<?php

namespace SwoftTest\Event\Fixture;

use Swoft\Event\EventInterface;
use Swoft\Event\EventHandlerInterface;

/**
 * Class TestHandler
 * @package SwoftTest\Event\Fixture
 */
class TestHandler implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        $pos = __METHOD__;
        echo "handle the event '{$event->getName()}' on the: $pos\n";

        return true;
    }
}
