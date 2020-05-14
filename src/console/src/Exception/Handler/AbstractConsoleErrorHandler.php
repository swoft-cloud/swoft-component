<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Exception\Handler;

use Swoft\Console\Contract\ConsoleErrorHandlerInterface;
use Swoft\Error\ErrorType;

/**
 * Class AbstractConsoleErrorHandler
 *
 * @since 2.0.3
 */
abstract class AbstractConsoleErrorHandler implements ConsoleErrorHandlerInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::CLI;
    }
}
