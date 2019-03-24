<?php declare(strict_types=1);


namespace Swoft\Http\Server\Listener;


use Swoft\Co;
use Swoft\Context\Context;
use Swoft\Db\ConnectionManager;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\HttpServerEvent;
use Swoft\Log\Logger;

/**
 * Class AfterRequestListener
 *
 * @since 2.0
 *
 * @Listener(HttpServerEvent::AFTER_REQUEST)
 */
class AfterRequestListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws \Exception
     */
    public function handle(EventInterface $event): void
    {
        /**
         * @var Response $response
         */
        $response = $event->getParam(0);
        $response->send();

        $tid = Co::tid();
        var_dump('sgo1-'.$tid);
        \sgo(function () use($tid){

            var_dump('sgo2-'.$tid);
            // Wait
            Context::getWaitGroup()->wait();
            var_dump('sgo3-'.$tid);

            /* @var Logger $logger */
            $logger = \bean('logger');

            // Add notice log
            if ($logger->isEnable()) {
                $logger->appendNoticeLog();
            }

            /* @var ConnectionManager $cm*/
            $cm = bean(ConnectionManager::class);
            $cm->release();

            // Destroy context
            Context::destroy();
        }, false);
    }
}
