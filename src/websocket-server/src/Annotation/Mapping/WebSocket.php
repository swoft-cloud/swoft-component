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
 * Class WebSocket - mark an websocket module handler
 * @package Swoft\WebSocket\Server\Bean\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("path", type="string")
 * )
 */
final class WebSocket
{
    /**
     * websocket route path
     *
     * @var string
     * @Required()
     */
    private $path = '/';

    /**
     * @var
     */
    private $messageParser;

    /**
     * dispatch message request
     *
     * @var bool
     */
    private $dispatchMessage = false;

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
    public function getPath(): string
    {
        return $this->path;
    }
}
