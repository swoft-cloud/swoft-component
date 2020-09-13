<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
 *     @Attribute("workerId", type="array"),
 * })
 */
class Process
{
    /**
     * Default run the first worker
     */
    public const DEFAULT = -1;

    /**
     * @var array
     */
    private $workerId = [
        self::DEFAULT
    ];

    /**
     * Process constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->workerId = (array)$values['value'];
        }
        if (isset($values['workerId'])) {
            $this->workerId = (array)$values['workerId'];
        }
    }

    /**
     * @return array
     */
    public function getWorkerId(): array
    {
        return $this->workerId;
    }
}
