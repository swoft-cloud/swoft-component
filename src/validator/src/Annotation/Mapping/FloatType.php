<?php declare(strict_types=1);


namespace Swoft\Validator\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class FloatType
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("message", type="string")
 * })
 */
class FloatType extends Type
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var float|null
     */
    private $default;

    /**
     * @var string
     */
    private $name = '';

    /**
     * FloatType constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->message = $values['value'];
        }
        if (isset($values['message'])) {
            $this->message = $values['message'];
        }
        if (isset($values['default'])) {
            $this->default = $values['default'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return float|null
     */
    public function getDefault(): ?float
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}