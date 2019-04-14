<?php declare(strict_types=1);

namespace Swoft\Error\Bootstrap\Listener;

use Swoft\Error\Annotation\Mapping\ExceptionHandlerParser;
use Swoft\Error\ErrorHandlers;
use Swoft\Error\ErrorType;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\SwoftEvent;

/**
 * Class WorkerStartListener
 *
 * @since 2.0
 * @Listener(SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        $chain = \Swoft::getBean(ErrorHandlers::class);

        $this->register($chain);

        CLog::info('Error manager init complete(%d handler)', $chain->count());
    }

    public function register(ErrorHandlers $chain): void
    {
        $handlers = ExceptionHandlerParser::getHandlers();

        foreach ($handlers as $handlerClass => $exceptions) {
            $type = ErrorType::DEF;

            foreach ($exceptions as $exceptionClass) {
                $chain->addHandler($exceptionClass, $handlerClass, $type);
            }
        }

        ExceptionHandlerParser::clear();
    }
}
