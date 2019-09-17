<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\ConnectionStorage;
use Swoft\WebSocket\Server\WsServerEvent;

/**
 * Class ConnStorageSubscriber
 *
 * @since 2.0.6
 * @Subscriber()
 */
class ConnStorageSubscriber implements EventSubscriberInterface
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
            WsServerEvent::HANDSHAKE_SUCCESS => 'handshakeOk',
            WsServerEvent::MESSAGE_RECEIVE   => 'messageReceive',
            WsServerEvent::CLOSE_BEFORE      => 'messageReceive',
        ];
    }

    /**
     * @return ConnectionStorage
     */
    protected function getStorage(): ConnectionStorage
    {
        return Swoft::getBean('wsConnStorage');
    }

    /**
     * @param EventInterface $event
     */
    public function handshakeOk(EventInterface $event): void
    {
        [$request, $response] = $event->getParams();

        $this->getStorage()->storage($request, $response);
    }

    /**
     * @param EventInterface $event
     */
    public function messageReceive(EventInterface $event): void
    {
        $fd = (int)$event->getTarget();

        if (!Session::has((string)$fd)) {
            $this->getStorage()->restore($fd);
        }
    }

    /**
     * @param EventInterface $event
     */
    public function connClose(EventInterface $event): void
    {
        $fd = (int)$event->getTarget();

        $this->getStorage()->remove($fd);
    }
}
