<?php declare(strict_types=1);


namespace Swoft\Process\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Process
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("workerId", type="int"),
 * })
 */
class Process
{
    /**
     * @var int
     */
    private $workerId = 0;

    /**
     * Process constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->workerId = $values['value'];
        }
        if (isset($values['workerId'])) {
            $this->workerId = $values['workerId'];
        }
    }

    /**
     * @return int
     */
    public function getWorkerId(): int
    {
        return $this->workerId;
    }
}