<?php

namespace Swoft\Bean\Annotation;

/**
 * 浮点数验证
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @uses      Floats
 * @version   2017年11月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Floats
{
    /**
     * @var string
     */
    private $from = ValidatorFrom::POST;

    /**
     * 字段名称
     *
     * @var string
     */
    private $name;

    /**
     * 最小值
     *
     * @var float
     */
    private $min;

    /**
     * 最小值
     *
     * @var float
     */
    private $max;

    /**
     * 默认值，如果是null，强制验证参数
     *
     * @var null|float
     */
    private $default = null;

    /**
     * Integer constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['from'])) {
            $this->from = $values['from'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['min'])) {
            $this->min = $values['min'];
        }
        if (isset($values['max'])) {
            $this->max = $values['max'];
        }
        if (isset($values['default'])) {
            $this->default = $values['default'];
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
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @return float|null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }
}
