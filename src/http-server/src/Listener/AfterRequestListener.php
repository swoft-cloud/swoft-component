<?php declare(strict_types=1);


namespace Swoft\Http\Server\Listener;


use Swoft\Context\Context;
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
        [$response] = $event->getParams();

        $response->send();

        /* @var Logger $logger */
        $logger = \bean('logger');

        // Add notice log
        $logger->appendNoticeLog();

        // Destroy context
        Context::destroy();
    }
}