<?php

namespace Swoft\Bean\Annotation;

/**
 * 字母和数组验证
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @uses      Alphanumeric
 * @version   2018年05月29日
 * @author    leno <leno@itdashu.com>
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Alphanumeric
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
     * Min value
     *
     * @var int
     */
    private $min = PHP_INT_MIN;

    /**
     * Max value
     *
     * @var int
     */
    private $max = PHP_INT_MAX;

    /**
     * @var string
     */
    private $template = '';

    /**
     * 默认值，如果是null，强制验证参数
     *
     * @var int
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
        if (isset($values['default'])) {
            $this->default = $values['default'];
        }
        if (isset($values['min'])) {
            $this->min = $values['min'];
        }
        if (isset($values['max'])) {
            $this->max = $values['max'];
        }
        if (isset($values['template'])) {
            $this->template = $values['template'];
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
     * @return int
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

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     *
     * @return Alphanumeric
     */
    public function setMin(int $min): Alphanumeric
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     *
     * @return Alphanumeric
     */
    public function setMax(int $max): Alphanumeric
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
}
