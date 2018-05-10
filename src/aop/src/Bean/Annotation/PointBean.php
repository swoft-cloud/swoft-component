<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Bean\Annotation;

/**
 * the point cut of bean
 *
 * @Annotation
 * @Target("CLASS")
 */
class PointBean
{
    /**
     * @var array
     */
    private $include = [];

    /**
     * @var array
     */
    private $exclude = [];

    /**
     * PointBean constructor.
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
