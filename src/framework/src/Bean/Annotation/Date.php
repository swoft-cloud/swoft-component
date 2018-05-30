<?php

namespace Swoft\Bean\Annotation;

/**
 * 日期验证
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @uses      Date
 * @version   2018年05月29日
 * @author    leno <leno@itdashu.com>
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Date
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
     * 日期格式
     *
     * @var string
     */
    private $format;

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
        if(isset($values['format'])){
            $this->format = $values['format'];
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
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $template
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
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
