<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Db
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("prop", type="string"),
 * })
 *
 * @since 2.0
 */
class Column
{
    /**
     * Database field name
     *
     * @var string
     */
    private $name = '';

    /**
     * Entity to array prop name
     *
     * @var string
     */
    private $prop = '';

    /**
     * Column constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }

        if (isset($values['prop'])) {
            $this->prop = $values['prop'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getProp(): string
    {
        return $this->prop;
    }
}