<?php

namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Bean
{
    /**
     * bean名称
     *
     * @var string
     */
    private $name = '';

    /**
     * bean类型
     *
     * @var int
     */
    private $scope = Scope::SINGLETON;

    /**
     * referenced bean, default is null
     *
     * @var string
     */
    private $ref = '';

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['scope'])) {
            $this->scope = $values['scope'];
        }
        if (isset($values['ref'])) {
            $this->ref = $values['ref'];
        }
    }

    /**
     * 获取bean名称
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 获取bean类型
     *
     * @return int
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * return name of referenced bean
     *
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }
}
