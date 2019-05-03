<?php declare(strict_types=1);


namespace Swoft\Validator\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class DoubleType
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("message", type="string")
 * })
 */
class DoubleType extends Type
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var double|null
     */
    private $default;

    /**
     * @var string
     */
    private $name = '';

    /**
     * DoubleType constructor.
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
     * @return double|null
     */
    public function getDefault(): ?double
    {
        if ($this->default === null) {
            return null;
        }

        return (double)$this->default;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}