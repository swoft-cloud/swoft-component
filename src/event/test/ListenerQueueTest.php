<?php

namespace SwoftTest\Event;

use Swoft\Event\Listener\ListenerQueue;
use PHPUnit\Framework\TestCase;

/**
 * Class ListenerQueueTest
 * @package SwoftTest\Event
 */
class ListenerQueueTest extends TestCase
{
    public function testQueue()
    {
        $cb0 = (object)'handler0';
        $cb1 = (object)'handler1';
        $cb2 = (object)'handler2';
        $cb3 = (object)'handler3';
        $cb4 = (object)'handler4';

        $lq = new ListenerQueue();

        $lq->add($cb0, 0);
        $lq->add($cb1, 1);
        $lq->add($cb2, 2);
        $lq->add($cb3, -2);
        $lq->add($cb4, 20);

        $this->assertCount(5, $lq);
        $this->assertSame($lq->getPriority($cb4), 20);
    }
}
