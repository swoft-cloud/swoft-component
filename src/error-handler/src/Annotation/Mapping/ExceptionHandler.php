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
 *     @Attribute("exception", type="string")
 * })
 */
class ExceptionHandler
{
    /**
     * Exception handler class
     *
     * @var string
     * @Required()
     */
    private $exception;

    /**
     * @var int
     */
    private $priority = 0;

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->exception = $values['value'];
        }

        if (isset($values['exception'])) {
            $this->exception = $values['exception'];
        }

        if (isset($values['priority'])) {
            $this->priority = $values['priority'];
        }
    }

    /**
     * @return string
     */
    public function getException(): string
    {
        return $this->exception;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
