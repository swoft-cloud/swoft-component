<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Db\Entity;

abstract class Schema
{
    // PHP类型
    const TYPE_INT    = 'int';

    const TYPE_STRING = 'string';

    const TYPE_FLOAT  = 'float';

    const TYPE_BOOL   = 'bool';

    /**
     * @var array entity映射关系
     */
    public $dbSchema;

    /**
     * @var array php映射关系
     */
    public $phpSchema;

    /**
     * @var string|null $driver 数据库驱动
     */
    private $driver;

    /**
     * 设置数据库驱动
     *
     * @param string $value
     *
     * @return Schema
     */
    public function setDriver(string $value): self
    {
        $this->driver = $value;

        return $this;
    }

    /**
     * 返回数据库驱动
     *
     * @return null|string
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
