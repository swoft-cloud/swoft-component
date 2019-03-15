<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class WsController
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("prefix", type="string"),
 *     @Attribute("module", type="string")
 * )
 */
final class WsController
{
    /**
     * Controller prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * The module class full name.
     * Which module does the controller belong to?
     *
     * @var string
     * @Required()
     */
    private $module;

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->prefix = (string)$values['value'];
        } elseif (isset($values['prefix'])) {
            $this->prefix = (string)$values['prefix'];
        }

        if (isset($values['module'])) {
            $this->module = (string)$values['module'];
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }
}
