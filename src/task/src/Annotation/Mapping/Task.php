<?php declare(strict_types=1);

namespace Swoft\Task\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Task
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *     @Attribute("name", type="string"),
 * })
 */
class Task
{
    /**
     * The task name.
     * If it is empty, it will be replaced with the class name.
     *
     * @var string
     */
    private $name = '';

    /**
     * Task constructor.
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
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
