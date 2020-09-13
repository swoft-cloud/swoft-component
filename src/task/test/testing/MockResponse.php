<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Task\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Task\Exception\TaskException;
use Swoft\Task\Packet;
use Swoft\Task\Response;

/**
 * Class MockResponse
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MockResponse extends Response
{
    /**
     * @return string
     * @throws TaskException
     */
    public function getResult()
    {
        $result = $this->getResponseData();

        [$result, $errorCode, $errorMessage] = Packet::unpackResponse($result);
        if ($errorCode !== null) {
            throw new TaskException(sprintf('%s(code=%d)', $errorMessage, $errorCode));
        }

        return $result;
    }

    public function send(): void
    {
    }
}
