<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Contract;

interface ResponseInterface
{
    /**
     * @return string
     */
    public function getResponseData(): string;

    /**
     * Send
     */
    public function send(): void;

    /**
     * @param mixed $result
     */
    public function setResult($result): void;

    /**
     * @param int|null $errorCode
     */
    public function setErrorCode(?int $errorCode): void;

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void;
}
