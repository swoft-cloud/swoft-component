<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Listener;

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

        // Don't handle on data is invalid
        $data = (array)json_decode($message, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return;
        }

        // Ensure is tcp notify message
        if (!isset($data['from']) || $data['from'] !== 'tcpServer') {
            return;
        }

        // Handle
        if (isset($data['event'])) {
            $eventName = (string)$data['event'];

            /** @see CloseListener::onClose() */
            if ($eventName === 'onClose') {
                $this->handleOnClose($data, $event->getParam(0));
            }
        }
    }

    /**
     * @param array $data
     * @param int   $srcWID
     */
    protected function handleOnClose(array $data, int $srcWID): void
    {
        $sid = $data['sid'];

        if (Session::has($sid)) {
            CLog::info("PipeMessage: destroy tcp connection for fd=$sid fromWID=$srcWID");
            Session::destroy($sid);
        }
    }
}
