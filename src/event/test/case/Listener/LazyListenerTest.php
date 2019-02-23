<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-01-19
 * Time: 22:50
 */

namespace SwoftTest\Event\Listener;

use PHPUnit\Framework\TestCase;
use Swoft\Event\Event;
use Swoft\Event\EventInterface;
use Swoft\Event\Listener\LazyListener;

/**
 * Class LazyListenerTest
 * @package SwoftTest\Event\Listener
 */
class LazyListenerTest extends TestCase
{
    public function testCall(): void
    {
        $listener = LazyListener::create(function (EventInterface $e) {
            $this->assertSame('lazy', $e->getName());
            return 'ABC';
        });

        $this->assertNotEmpty($listener->getCallback());
        $this->assertSame('ABC', $listener->handle(Event::create('lazy')));
    }
}
