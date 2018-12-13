<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Message;

/**
 * Class MessageDispatcher
 * @package Swoft\WebSocket\Server\Message
 */
class MessageDispatcher
{
    /**
     * @var array
     * [
     *   'name' => 'callback'
     * ]
     */
    protected $handlers = [];

    public function __construct(array $handlers = [])
    {
    }

    /**
     * @param string $event
     * @param array $body
     */
    public function dispatch(string $event, array $body)
    {
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param array $handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
    }
}
