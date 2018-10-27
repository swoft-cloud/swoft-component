<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Db\Pool\Config;

use Swoft\Db\Driver\Driver;
use Swoft\Pool\PoolProperties;

/**
 * The pool properties of database
 */
class DbPoolProperties extends PoolProperties
{
    /**
     * The default of driver
     *
     * @var string
     */
    protected $driver = Driver::MYSQL;

    /**
     * 开启严格模式，返回的字段将自动转为数字类型
     *
     * @var bool
     */
    protected $strictType = false;

    /**
     * 开启 Fetch 模式, 可类似于 PDO 一样使用 fetch/fetchAll 逐行获取或获取全部结果集
     *
     * @since Swoole 4.0
     * @var bool
     */
    protected $fetchMode = true;

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function isStrictType(): bool
    {
        return $this->strictType;
    }

    public function isFetchMode(): bool
    {
        return $this->fetchMode;
    }
}
