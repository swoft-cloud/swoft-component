<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Exception;

use Swoft\Error\ErrorType;
use Swoft\WebSocket\Server\Contract\HandShakeErrorHandlerInterface;

/**
 * Class AbstractHandShakeErrorHandler
 * @since 2.0
 */
abstract class AbstractHandShakeErrorHandler implements HandShakeErrorHandlerInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::WS_HS;
    }
}
