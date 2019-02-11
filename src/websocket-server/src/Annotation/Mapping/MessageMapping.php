<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 17:19
 */

namespace Swoft\WebSocket\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * Class MessageMapping - Use for mark websocket message request command handler method
 *
 * @since 2.0
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
     * @var string
     * @Required()
     */
    private $command = '';

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->path = (string)$values['value'];
        } elseif (isset($values['path'])) {
            $this->path = (string)$values['path'];
        }
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}
