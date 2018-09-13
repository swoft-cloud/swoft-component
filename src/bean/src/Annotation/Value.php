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
 * 1. 注入值
 * 2. 注入property配置文件值
 * 3. 注入env环境变量
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Value
{
    /**
     * Property name
     */
    private $name = '';

    /**
     * Env name
     */
    private $env = '';

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['env'])) {
            $this->env = $values['env'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnv(): string
    {
        return $this->env;
    }
}
