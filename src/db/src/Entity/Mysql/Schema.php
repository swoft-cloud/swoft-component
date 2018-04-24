<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Db\Entity\Mysql;

/**
 * MYSQL数据库字段映射关系
 *
 * @uses      Schema
 * @version   2017年11月14日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */

class Schema extends \Swoft\Db\Entity\Schema
{
    /**
     * @var array entity映射关系
     */
    public $dbSchema = [
        'int'      => 'Types::INT',
        'char'     => 'Types::STRING',
        'varchar'  => 'Types::STRING',
        'text'     => 'Types::STRING',
        'datetime' => 'Types::DATETIME',
        'float'    => 'Types::FLOAT',
        'number'   => 'Types::NUMBER',
        'decimal'  => 'Types::FLOAT',
        'bool'     => 'Types::BOOLEAN',
        'tinyint'  => 'Types::INT',
        'mediumint'=> 'Types::INT',
        'smallint' => 'Types::INT,'
    ];

    /**
     * @var array php映射关系
     */
    public $phpSchema = [
        'int'      => self::TYPE_INT,
        'char'     => self::TYPE_STRING,
        'varchar'  => self::TYPE_STRING,
        'text'     => self::TYPE_STRING,
        'datetime' => self::TYPE_STRING,
        'float'    => self::TYPE_FLOAT,
        'number'   => self::TYPE_INT,
        'decimal'  => self::TYPE_FLOAT,
        'bool'     => self::TYPE_BOOL,
        'tinyint'  => self::TYPE_INT,
        'mediumint'=> self::TYPE_INT,
        'smallint' => self::TYPE_INT
    ];
}
