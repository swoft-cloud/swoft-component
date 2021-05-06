<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Log\Error;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Contract\PipeMessageInterface;
use Swoft\Server\ServerEvent;
use Swoft\SwoftEvent;
use Swoole\Server;
use Swoft\Server\Context\PipeMessageContext;
use Throwable;

/**
 * Class PipeMessageListener
 *
 * @since 2.0.7
 * @Bean()
 */
class PipeMessageListener implements PipeMessageInterface
{
    /**
     * Pipe message event handle
     *
     * @param Server $server
     * @param int    $srcWorkerId
     * @param mixed  $message
     */
    public function onPipeMessage(Server $server, int $srcWorkerId, $message): void
    {
        Context::set(PipeMessageContext::new($srcWorkerId, $message));

        CLog::debug("PipeMessage: received pipe-message fromWID={$srcWorkerId}}");

        try {

            Swoft::trigger(ServerEvent::PIPE_MESSAGE, $message, $srcWorkerId, $server);

        } catch (Throwable $e) {

            Error::log("PipeMessage handle fails: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}");

        } finally {

            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

        }
    }
}
