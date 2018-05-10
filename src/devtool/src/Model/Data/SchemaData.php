<?php

namespace Swoft\Devtool\Model\Data;

use Swoft\Bean\Annotation\Bean;
use Swoft\Db\Driver\Driver;
use Swoft\Db\Driver\Mysql\Schema as MysqlSchema;
use Swoft\Db\Driver\Pgsql\Schema as PgsqlSchema;
use Swoft\Devtool\Model\Dao\MysqlSchemaDao;
use Swoft\Devtool\Model\Dao\SchemaInterface;
use Swoft\Exception\Exception;
use Swoft\Helper\StringHelper;

/**
 * SchemaData
 * @Bean()
 */
class SchemaData
{
    /**
     * @param string $driver
     * @param string $db
     * @param string $inc
     * @param string $exc
     * @param string $tablePrefix
     *
     * @return array
     */
    public function getSchemaTableData(string $driver, string $db, string $inc, string $exc, string $tablePrefix): array
    {
        $schemaDao = $this->getSchemaDao($driver);
        $schemas   = $schemaDao->getTableSchema($db, $inc, $exc);
        foreach ($schemas as &$schema) {
            if (empty($tablePrefix)) {
                $mapping = $schema['name'];
            } else {
                $mapping = StringHelper::replaceFirst($tablePrefix, '', $schema['name']);
            }

            $schema['mapping'] = ucfirst(StringHelper::camel($mapping));
        }

        return $schemas;
    }

    /**
     * @param string $driver
     * @param string $db
     * @param string $table
     * @param string $fieldPrefix
     *
     * @return array
     */
    public function getSchemaColumnsData(string $driver, string $db, string $table, string $fieldPrefix): array
    {
        $schemaDao = $this->getSchemaDao($driver);
        list($mapingTypes, $phpTypes) = $this->getSchemaTypes($driver);
        $columnSchemas = $schemaDao->getColumnsSchema($db, $table);

        foreach ($columnSchemas as &$columnSchema) {
            $type = $columnSchema['type'];
            if (empty($fieldPrefix)) {
                $mappingName = $columnSchema['name'];
            } else {
                $mappingName = StringHelper::replaceFirst($fieldPrefix, '', $columnSchema['name']);
            }

            $columnSchema['mappingName'] = StringHelper::camel($mappingName);
            $columnSchema['mappingVar']  = sprintf('$%s', $columnSchema['mappingName']);
            $columnSchema['mappingType'] = $mapingTypes[$type]??'';
            $columnSchema['phpType']     = $phpTypes[$type]??'';
        }

        return $columnSchemas;
    }

    /**
     * @param string $driver
     *
     * @return \Swoft\Devtool\Model\Dao\SchemaInterface
     * @throws \Swoft\Exception\Exception
     */
    private function getSchemaDao(string $driver): SchemaInterface
    {
        if ($driver == Driver::MYSQL) {
            return \bean(MysqlSchemaDao::class);
        }

        throw new Exception(sprintf('The %s driver does not support!', $driver));
    }

    /**
     * @param string $driver
     *
     * @return array
     * @throws \Swoft\Exception\Exception
     */
    private function getSchemaTypes(string $driver): array
    {
        if ($driver == Driver::MYSQL) {
            return [MysqlSchema::$typeMap, MysqlSchema::$phpMap];
        }
        if ($driver == Driver::PGSQL) {
            return [PgsqlSchema::$typeMap, PgsqlSchema::$phpMap];
        }

        throw new Exception(sprintf('The %s schema does not support!', $driver));
    }
}