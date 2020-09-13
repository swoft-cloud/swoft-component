<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class PointExecution
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("include", type="array"),
 *     @Attribute("exclude", type="array"),
 * })
 */
final class PointExecution
{
    /**
     * Include regular expression
     *
     * @var array
     */
    private $include = [];

    /**
     * Exclude regular expression
     *
     * @var array
     */
    private $exclude = [];

    /**
     * PointExecution constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->include = $values['value'];
        }
        if (isset($values['include'])) {
            $this->include = $values['include'];
        }
        if (isset($values['exclude'])) {
            $this->exclude = $values['exclude'];
        }
    }

    /**
     * @return array
     */
    public function getInclude(): array
    {
        return $this->include;
    }

    /**
     * @return array
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }
}
