<?php declare(strict_types=1);


namespace Swoft\Http\Server\Listener;


use Swoft\Bean\Exception\PrototypeException;
use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Message\Response;
use Swoft\Http\Message\ServerRequest;
use Swoft\Http\Server\HttpContext;
use Swoft\Http\Server\HttpServerEvent;

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
     */
    public function handle(EventInterface $event): void
    {
        /**
         * @var ServerRequest $request
         * @var Response      $response
         */
        [$request, $response] = $event->getParams();

        $data = [
            'traceid'     => $request->headerLine('traceid', uniqid()),
            'spanid'      => $request->headerLine('spanid', uniqid()),
            'uri'         => $request->getUri()->getPath(),
            'requestTime' => microtime(true),
        ];

        $httpContext = HttpContext::new($request, $response);
        $httpContext->setMulti($data);

        Context::set($httpContext);
    }
}