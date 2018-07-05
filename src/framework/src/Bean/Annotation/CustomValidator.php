<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
    protected $from = ValidatorFrom::POST;

    /**
     * Parameter name
     *
     * @var string
     */
    protected $name;

    /**
     * 默认值，如果是null，强制验证参数
     *
     * @var null|string
     */
    protected $default = null;

    /**
     * 验证器
     *
     * @var null|string
     */
    protected $validator = null;

    /**
     * @var string
     */
    protected $template = '';

    /**
     * 是否抛出异常
     *
     * @var bool
     */
    protected $throw = true;

    /**
     * CustomValidator constructor.
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
        if (isset($values['template'])) {
            $this->template = $values['template'];
        }
        if (isset($values['throw'])) {
            $this->throw = $values['throw'];
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
     * @return CustomValidator
     */
    public function setFrom(string $from): CustomValidator
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
     * @return CustomValidator
     */
    public function setName(string $name): CustomValidator
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
     * @return CustomValidator
     */
    public function setDefault($default): CustomValidator
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
     * @return CustomValidator
     */
    public function setValidator($validator): CustomValidator
    {
        $this->validator = $validator;

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
     *
     * @return CustomValidator
     */
    public function setTemplate(string $template): CustomValidator
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return bool
     */
    public function getThrow(): bool
    {
        return $this->throw;
    }

    /**
     * @param bool $throw
     *
     * @return CustomValidator
     */
    public function setThrow(bool $throw): CustomValidator
    {
        $this->throw = $throw;

        return $this;
    }
}
