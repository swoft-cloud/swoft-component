<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Error\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\Contract\DefaultErrorHandlerInterface;
use Swoft\Error\ErrorType;
use Throwable;

/**
 * Class CustomErrorHandler
 *
 * @Bean()
 */
class CustomErrorHandler implements DefaultErrorHandlerInterface
{
    private $message = '';

    /**
     * @param Throwable $e
     *
     * @return void
     */
    public function handle(Throwable $e): void
    {
        $this->message = 'HI, HANDLED: ' . $e->getMessage();
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::DEF;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
