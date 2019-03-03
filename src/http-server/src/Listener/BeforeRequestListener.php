<?php declare(strict_types=1);


namespace Swoft\Http\Server\Listener;


use Swoft\Bean\Exception\PrototypeException;
use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Message\Response;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\HttpContext;
use Swoft\Http\Server\HttpServerEvent;
use Swoft\Log\Logger;

/**
 * Class BeforeRequestListener
 *
 * @since 2.0
 *
 * @Listener(HttpServerEvent::BEFORE_REQUEST)
 */
class BeforeRequestListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws PrototypeException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        [$request, $response] = $event->getParams();

        $httpContext = HttpContext::new($request, $response);

        /* @var Logger $logger */
        $logger = \bean('logger');

        // Add log data
        if ($logger->isEnable()) {
            $data = [
                'traceid'     => $request->headerLine('traceid', ''),
                'spanid'      => $request->headerLine('spanid', ''),
                'uri'         => $request->getUri()->getPath(),
                'requestTime' => $request->getRequestTime(),
            ];

            $httpContext->setMulti($data);
        }

        Context::set($httpContext);
    }
}