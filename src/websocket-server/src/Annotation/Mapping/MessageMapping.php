<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class MessageMapping - Use for mark websocket message request command handler method
 *
 * @since   2.0
 * @package Swoft\WebSocket\Server\Annotation\Mapping
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes(
 *     @Attribute("command", type="string")
 * )
 */
final class MessageMapping
{
    /**
     * @var bool
     */
    private $root = false;

    /**
     * @var string
     * @Required()
     */
    private $command = '';

    /**
     * Default opcode of the command. please see WEBSOCKET_OPCODE_*
     *
     * @var int
     */
    private $opcode = 0;

    /**
     * Middleware for the method
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->command = (string)$values['value'];
        } elseif (isset($values['command'])) {
            $this->command = (string)$values['command'];
        }

        if (isset($values['root'])) {
            $this->root = (bool)$values['root'];
        }

        if (isset($values['opcode'])) {
            $this->opcode = (int)$values['opcode'];
        }

        if (isset($values['middlewares'])) {
            $this->middlewares = (array)$values['middlewares'];
        }
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->root;
    }

    /**
     * @return int
     */
    public function getOpcode(): int
    {
        return $this->opcode;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
