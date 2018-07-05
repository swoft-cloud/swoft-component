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
     * @var string
     */
    private $template = '';

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
}
