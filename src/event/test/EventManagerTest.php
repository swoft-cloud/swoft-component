<?php

namespace SwoftTest\Event;

use Swoft\Event\Event;
use Swoft\Event\EventInterface;
use Swoft\Event\Manager\EventManager;
use Swoft\Event\Manager\EventManagerInterface;
use SwoftTest\Event\Fixture\TestHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class EventManagerTest
 */
class EventManagerTest extends TestCase
{
    public function testCreate()
    {
        $em = new EventManager();

        $this->assertInstanceOf(EventManagerInterface::class, $em);
    }

    public function testAttach()
    {
        $em = new EventManager();
        $em->attach('test', new TestHandler());
        $em->attach('test', function () {
            //
        });

        $this->assertCount(2, $em->getListeners('test'));
    }

    public function testPriority()
    {
        $l0 = new TestHandler();
        $l1 = function () {
            //
        };

        $em = new EventManager();
        $em->attach('test', $l0);
        $em->attach('test', $l1, 5);

        $this->assertEquals(0, $em->getListenerPriority($l0, 'test'));
        $this->assertEquals(5, $em->getListenerPriority($l1, 'test'));
    }

    public function testTrigger()
    {
        $l0 = new class
        {
            public function __invoke(Event $evt)
            {
                $evt->addParam('key1', 'val1');
                $evt->setParam('key', 'new val');
            }
        };
        $l1 = function (EventInterface $evt) {
            $evt->setTarget('new target');
        };

        $em = new EventManager();
        $em->attach('test', $l0);
        $em->attach('test', $l1, 5);

        $evt = $em->trigger('test', 'target', ['key' => 'val']);

        $this->assertInstanceOf(EventInterface::class, $evt);
        $this->assertEquals('new target', $evt->getTarget());
        $this->assertEquals('new val', $evt->getParam('key'));
        $this->assertArrayHasKey('key1', $evt->getParams());
    }
}
