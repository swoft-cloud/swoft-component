<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Db\Command;

use Swoft\App;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Db\Entity\Generator;
use Swoft\Db\Entity\Mysql\Schema;
use Swoft\Db\Pool\DbPool;

/**
 * the group command list of database entity
 *
 * @Command(coroutine=false)
 */
class EntityCommand
{
    /**
     * @var \Swoft\Db\Entity\Schema $schema schema对象
     */
    private $schema;

    /**
     * @var Generator $generatorEntity 实体实例
     */
    private $generatorEntity;

    /**
     * @var string $filePath 实体文件路径
     */
    private $filePath = '@app/Models/Entity';

    /**
     * Auto create entity by table structure
     *
     * @Usage
     * entity:create -d[|--database] <database>
     * entity:create -d[|--database] <database> [table]
     * entity:create -d[|--database] <database> -i[|--include] <table>
     * entity:create -d[|--database] <database> -i[|--include] <table1,table2>
     * entity:create -d[|--database] <database> -i[|--include] <table1,table2> -e[|--exclude] <table3>
     * entity:create -d[|--database] <database> -i[|--include] <table1,table2> -e[|--exclude] <table3,table4>
     *
     * @Options
     * -d  数据库
     * --database  数据库
     * -i  指定特定的数据表，多表之间用逗号分隔
     * --include  指定特定的数据表，多表之间用逗号分隔
     * -e  排除指定的数据表，多表之间用逗号分隔
     * --exclude  排除指定的数据表，多表之间用逗号分隔
     *
     * @Example
     * php bin/swoft entity:create -d test
     */
    public function create()
    {
        $this->initDatabase();

        $database = '';
        $tablesEnabled = $tablesDisabled = [];

        $this->parseDatabaseCommand($database);
        $this->parseEnableTablesCommand($tablesEnabled);
        $this->parseDisableTablesCommand($tablesDisabled);

        if (empty($database)) {
            output()->writeln('databases doesn\'t not empty!');
        } else {
            $this->generatorEntity->db = $database;
            $this->generatorEntity->tablesEnabled = $tablesEnabled;
            $this->generatorEntity->tablesDisabled = $tablesDisabled;
            $this->generatorEntity->execute($this->schema);
        }
    }

    /**
     * 初始化方法
     */
    private function initDatabase(): bool
    {
        App::setAlias('@entityPath', $this->filePath);
        $pool = App::getBean(DbPool::class);
        $schema = new Schema();
        $schema->setDriver('MYSQL');
        $this->schema = $schema;
        $syncDbConnect = $pool->createConnection();
        $this->generatorEntity = new Generator($syncDbConnect);

        return true;
    }

    /**
     * 解析需要扫描的数据库
     *
     * @param string &$database 需要扫描的数据库
     */
    private function parseDatabaseCommand(string &$database)
    {
        if (input()->hasSOpt('d') || input()->hasLOpt('database')) {
            $database = (string)\input()->getSameOpt(['d','database']);
        }
    }

    /**
     * 解析需要扫描的table
     *
     * @param array &$tablesEnabled 需要扫描的表
     */
    private function parseEnableTablesCommand(&$tablesEnabled)
    {
        if (input()->hasSOpt('i') || input()->hasLOpt('include')) {
            $tablesEnabled = input()->hasSOpt('i') ? input()->getShortOpt('i') : input()->getLongOpt('include');
            $tablesEnabled = !empty($tablesEnabled) ? explode(',', $tablesEnabled) : [];
        }

        // 参数优先级大于选项
        if (!empty(input()->getArg(0))) {
            $tablesEnabled = [input()->getArg(0)];
        }
    }

    /**
     * 解析不需要扫描的table
     *
     * @param array &$tablesDisabled 不需要扫描的表
     */
    private function parseDisableTablesCommand(&$tablesDisabled)
    {
        if (input()->hasSOpt('e') || input()->hasLOpt('exclude')) {
            $tablesDisabled = input()->hasSOpt('e') ? input()->getShortOpt('e') : input()->getLongOpt('exclude');
            $tablesDisabled = !empty($tablesDisabled) ? explode(',', $tablesDisabled) : [];
        }
    }
}
