<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Crontab;

use Swoft\Memory\Table;

/**
 * Class TableCrontab
 * @package Swoft\Task\Crontab
 */
class TableCrontab
{
    /**
     * @const 内存表大小
     */
    const TABLE_SIZE = 1024;

    /**
     * @var int $taskCount Maximum number of tasks
     */
    public static $taskCount = 1024;

    /**
     * @var int $taskQueue Maximum number of queues
     */
    public static $taskQueue = 1024;

    /**
     * @var TableCrontab $instance 实例对象
     */
    private static $instance;

    /**
     * @var \Swoft\Memory\Table $originTable Memory task table
     */
    private $originTable;

    /**
     * @var \Swoft\Memory\Table $runTimeTable Memory operation table
     */
    private $runTimeTable;

    /**
     * @var array $originStruct 任务表结构
     */
    private $originStruct = [
        'rule'       => [\Swoole\Table::TYPE_STRING, 100],
        'taskClass'  => [\Swoole\Table::TYPE_STRING, 255],
        'taskMethod' => [\Swoole\Table::TYPE_STRING, 255],
        'add_time'   => [\Swoole\Table::TYPE_STRING, 11],
    ];

    /**
     * @var array $runTimeStruct 运行表结构
     */
    private $runTimeStruct = [
        'taskClass'  => [\Swoole\Table::TYPE_STRING, 255],
        'taskMethod' => [\Swoole\Table::TYPE_STRING, 255],
        'minute'      => [\Swoole\Table::TYPE_STRING, 20],
        'sec'        => [\Swoole\Table::TYPE_STRING, 20],
        'runStatus'  => [\Swoole\TABLE::TYPE_INT, 4],
    ];

    /**
     * 创建配置表
     *
     * @param int $taskCount Maximum number of tasks
     * @param int $taskQueue 最大队列数
     */
    public static function init(int $taskCount = null, int $taskQueue = null)
    {
        self::$taskCount = $taskCount ?? self::$taskCount;
        self::$taskQueue = $taskQueue ?? self::$taskQueue;

        self::getInstance();

        self::$instance->initTables();
    }

    /**
     * 获取实例对象
     */
    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 设置内存任务表实例
     *
     * @param Table $table 内存表
     */
    public function setOriginTable(Table $table)
    {
        $this->originTable = $table;
    }

    /**
     * Get memory task table instance
     * @return Table
     */
    public function getOriginTable(): Table
    {
        return $this->originTable;
    }

    /**
     * 设置执行任务表实例
     *
     * @param Table $table 执行任务表
     */
    public function setRunTimeTable(Table $table)
    {
        $this->runTimeTable = $table;
    }

    /**
     * 获取执行任务表实例
     *
     * @return Table
     */
    public function getRunTimeTable(): Table
    {
        return $this->runTimeTable;
    }

    /**
     * 初始化任务表
     * @throws \Swoft\Memory\Exception\RuntimeException
     * @throws \Swoft\Memory\Exception\InvalidArgumentException
     */
    private function initTables(): bool
    {
        return $this->createOriginTable() && $this->createRunTimeTable();
    }

    /**
     * 创建originTable
     *
     * @return bool
     * @throws \Swoft\Memory\Exception\RuntimeException
     * @throws \Swoft\Memory\Exception\InvalidArgumentException
     */
    private function createOriginTable(): bool
    {
        $this->setOriginTable(new Table('origin', self::TABLE_SIZE, $this->originStruct));

        return $this->getOriginTable()->create();
    }

    /**
     * 创建runTimeTable
     *
     * @return bool
     * @throws \Swoft\Memory\Exception\RuntimeException
     * @throws \Swoft\Memory\Exception\InvalidArgumentException
     */
    private function createRunTimeTable(): bool
    {
        $this->setRunTimeTable(new Table('runTime', self::TABLE_SIZE, $this->runTimeStruct));

        return $this->getRunTimeTable()->create();
    }
}
