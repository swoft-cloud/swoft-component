<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\ConnectionStorage;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoft\Config\Annotation\Mapping\Config;

/**
 * Class ConnStorageSubscriber
 *
 * @since 2.0.6
 * @deprecated please use ConnectionManager instead, will remove it on 2.0.10+
 * @Subscriber()
 */
class ConnStorageSubscriber implements EventSubscriberInterface
{
    /**
     * @Config("websocket.autoRestoreConn")
     * @var bool
     */
    private $autoRestoreConn = false;

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
            WsServerEvent::CLOSE_BEFORE      => 'connClose',
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
        if (!$this->autoRestoreConn) {
            return;
        }

        $request = $event->getParam(0);

        $this->getStorage()->storage($request);
    }

    /**
     * @param EventInterface $event
     */
    public function messageReceive(EventInterface $event): void
    {
        if (!$this->autoRestoreConn) {
            return;
        }

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
        if (!$this->autoRestoreConn) {
            return;
        }

        $fd = (int)$event->getTarget();

        $this->getStorage()->remove($fd);
    }

    /**
     * @return bool
     */
    public function isAutoRestoreConn(): bool
    {
        return $this->autoRestoreConn;
    }

    /**
     * @param bool $autoRestoreConn
     */
    public function setAutoRestoreConn(bool $autoRestoreConn): void
    {
        $this->autoRestoreConn = $autoRestoreConn;
    }
}
