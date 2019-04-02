<?php declare(strict_types=1);

namespace Swoft\ErrorHandler\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ExceptionHandler
 *
 * @since 2.0
 * @Annotation
 * @Target({"METHOD"})
 * @Attributes({
 *     @Attribute("priority", type="integer"),
 *     @Attribute("exceptions", type="array")
 * })
 */
class ExceptionHandler
{
    /**
     * @var int
     */
    private $priority = 0;

    /**
     * Exception handler classes
     *
     * @var string[]
     * @Required()
     */
    private $exceptions;

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->exceptions = (array)$values['value'];
        }

        if (isset($values['exceptions'])) {
            $this->exceptions = (array)$values['exceptions'];
        }

        if (isset($values['priority'])) {
            $this->priority = (int)$values['priority'];
        }
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return string[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
