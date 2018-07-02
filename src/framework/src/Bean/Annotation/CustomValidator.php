<?php

namespace Swoft\Bean\Annotation;

/**
 * Custom validator
 *
 * @Annotation
 * @Target("METHOD")
 */
class CustomValidator
{
    /**
     * @var string
     */
    private $from = ValidatorFrom::POST;

    /**
     * Parameter name
     *
     * @var string
     */
    private $name;

    /**
     * 默认值，如果是null，强制验证参数
     *
     * @var null|string
     */
    private $default = null;

    /**
     * 验证器
     *
     * @var null|string
     */
    private $validator = null;

    /**
     * Strings constructor.
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
        if (isset($values['validator'])) {
            $this->validator = $values['validator'];
        }
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return Strings
     */
    public function setFrom(string $from): Strings
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Strings
     */
    public function setName(string $name): Strings
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null|string $default
     *
     * @return Strings
     */
    public function setDefault($default): Strings
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param null|string $validator
     *
     * @return Strings
     */
    public function setValidator($validator): Strings
    {
        $this->validator = $validator;

        return $this;
    }
}