<?php

namespace Swoft\Http\Server\Annotation\Mapping;


use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Controller
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("prefix", type="string"),
 * })
 *
 * @since 2.0
 */
final class Controller
{
    /**
     * Route group prefix for the controller
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Controller constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->prefix = $values['value'];
        }
        if (isset($values['prefix'])) {
            $this->prefix = $values['prefix'];
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}