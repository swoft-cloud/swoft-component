<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/18
 * Time: 下午1:06
 */

namespace Swoft\WebSocket\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class WebSocket - mark an websocket connection/module handler
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("path", type="string")
 * )
 */
final class WsModule
{
    /**
     * websocket route path
     *
     * @var string
     * @Required()
     */
    private $path = '/';

    /**
     * Message parser class
     * @var string
     */
    private $messageParser;

    /**
     * Default message command
     * @var string
     */
    private $defaultCommand = 'index';

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

        if (isset($values['messageParser'])) {
            $this->messageParser = $values['messageParser'];
        }

        if (isset($values['defaultCommand'])) {
            $this->defaultCommand = $values['defaultCommand'];
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMessageParser(): string
    {
        return $this->messageParser;
    }

    /**
     * @return string
     */
    public function getDefaultCommand(): string
    {
        return $this->defaultCommand;
    }
}
