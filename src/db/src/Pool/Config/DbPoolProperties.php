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
 *
 * @uses      DbPoolProperties
 * @version   2018年01月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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
     * @var bool
     */
    protected $strictType = false;

    /**
     * 开启fetch模式, 可与pdo一样使用fetch/fetchAll逐行或获取全部结果集(4.0版本以上)
     * @var bool
     */
    protected $fetchMode = true;

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @return bool
     */
    public function isStrictType(): bool
    {
        return $this->strictType;
    }

    /**
     * @return bool
     */
    public function isFetchMode(): bool
    {
        return $this->fetchMode;
    }
}
