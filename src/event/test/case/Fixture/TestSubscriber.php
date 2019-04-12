<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2129-12-12
 * Time: 22:51
 */

namespace SwoftTest\Event\Fixture;

use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Event\Listener\ListenerPriority;

/**
 * Class TestSubscriber
 * @package SwoftTest\Event\Fixture
 * @Subscriber()
 */
class TestSubscriber implements EventSubscriberInterface
{
    public const EVENT_ONE = 'test.event1';
    public const EVENT_TWO = 'test.event2';

    /**
     * Configure events and corresponding processing methods (you can configure the priority)
     * @return array
     * [
     *  'event name' => 'handler method'
     *  'event name' => ['handler method', priority]
     * ]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            self::EVENT_ONE => 'handleEvent1',
            self::EVENT_TWO => ['handleEvent2', ListenerPriority::HIGH],
        ];
    }

    public function handleEvent1(EventInterface $evt): void
    {
        $evt->setParams(['msg' => 'handle the event: test.event1 position: TestSubscriber.handleEvent1()']);
    }

    public function handleEvent2(EventInterface $evt): void
    {
        $evt->setParams(['msg' => 'handle the event: test.event2 position: TestSubscriber.handleEvent2()']);
    }
}
