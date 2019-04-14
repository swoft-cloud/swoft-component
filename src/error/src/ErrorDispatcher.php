<?php declare(strict_types=1);

namespace Swoft\Error;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ErrorDispatcher
 *
 * @since 2.0
 * @Bean()
 */
class ErrorDispatcher
{
    /**
     * @param \Throwable $e
     * @param int        $type
     * @throws \Throwable
     * @return mixed
     */
    public function run(\Throwable $e, int $type = ErrorType::DEF)
    {
        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);
        $handler  = $handlers->matchHandler($e);

        try {
            // Call error handler
            $handler->handle($e);
        } catch (\Throwable $t) {
            /** @var DefaultErrorDispatcher $defDispatcher */
            $defDispatcher = \Swoft::getSingleton(DefaultErrorDispatcher::class);
            $defDispatcher->run($e);
        }
    }
}
