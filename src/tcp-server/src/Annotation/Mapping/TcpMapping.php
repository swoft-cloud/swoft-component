<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class TcpMapping - Use for mark tcp message request command handler method
 *
 * @since   2.0.3
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes(
 *     @Attribute("command", type="string")
 * )
 */
final class TcpMapping
{
    /**
     * @var string
     * @Required()
     */
    private $route = '';

    /**
     * Routing path params binding. eg. ["id" => "\d+"]
     *
     * @var array
     */
    private $params = [];

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->route = (string)$values['value'];
        } elseif (isset($values['route'])) {
            $this->route = (string)$values['route'];
        }

        if (isset($values['params'])) {
            $this->route = (array)$values['params'];
        }
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
