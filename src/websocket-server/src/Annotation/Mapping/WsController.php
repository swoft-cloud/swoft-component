<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-13
 * Time: 10:24
 */

namespace Swoft\WebSocket\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class WsController
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("prefix", type="string")
 * )
 */
final class WsController
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
