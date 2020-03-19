<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Testing\Listener;

use Swoft\Event\Event;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Tcp\Server\TcpServerEvent;
use SwoftTest\Testing\TempArray;

/**
 * Class TcpEventSubscriber
 *
 * @package SwoftTest\Tcp\Server\Testing\Listener
 */
class TcpEventSubscriber implements EventSubscriberInterface
{
    /**
     * Configure events and corresponding processing methods (you can configure the priority)
     *
     * @return array
     * [
     *  'event name' => 'handler method'
     *  'event name' => ['handler method', priority]
     * ]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TcpServerEvent::RECEIVE_BEFORE => 'handle',
        ];
    }

    public function handle(Event $event): void
    {
        TempArray::set($event->getName(), __METHOD__);
    }
}
