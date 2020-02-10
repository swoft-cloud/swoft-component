<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\ServerEvent;
use Swoft\Session\Session;

/**
 * Class PipeMessageListener
 *
 * @Listener(ServerEvent::PIPE_MESSAGE)
 */
class PipeMessageListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        if (!$message = $event->getTarget()) {
            return;
        }

        $data = (array)json_decode($message, true);

        // Don't handle on data is invalid
        if (JSON_ERROR_NONE !== json_last_error()) {
            return;
        }

        // Ensure is websocket notify message
        if (!isset($data['from']) || $data['from'] !== 'wsServer') {
            return;
        }

        // Handle
        if (isset($data['event'])) {
            $eventName = (string)$data['event'];

            /** @see \Swoft\WebSocket\Server\Swoole\CloseListener::onClose() */
            if ($eventName === 'onClose') {
                $this->handleClose($data, $event->getParam(0));
            }
        }
    }

    /**
     * @param array $data
     * @param int   $srcWID
     */
    protected function handleClose(array $data, int $srcWID): void
    {
        $sid = $data['sid'];
        if (Session::has($sid)) {
            CLog::info("PipeMessage: destroy ws connection for fd=$sid fromWID=$srcWID");
            Session::destroy($sid);
        }
    }
}
