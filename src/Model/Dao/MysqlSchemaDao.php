<?php

namespace Swoft\Devtool\Model\Dao;

use Swoft\Bean\Annotation\Bean;
use Swoft\Db\Query;

/**
 * Schema
 * @Bean()
 */
class MysqlSchemaDao implements SchemaInterface
{
    /**
     * @param string $db
     * @param string $inc
     * @param string $exc
     *
     * @return array
     */
    public function getTableSchema(string $db, string $inc, string $exc): array
    {
        $query = Query::table('information_schema.`tables`')->where('TABLE_SCHEMA', $db)->where('TABLE_TYPE', 'BASE TABLE');
        if (!empty($inc)) {
            $query = $query->whereIn('TABLE_NAME', explode(',', $inc));
        }

        if (!empty($exc)) {
            $query = $query->whereNotIn('TABLE_NAME', explode(',', $exc));
        }

        $columns = [
            'TABLE_NAME'    => 'name',
            'TABLE_COMMENT' => 'comment',
        ];

        return $query->get($columns)->getResult();
    }

    /**
     * @param string $db
     * @param string $table
     *
     * @return array
     */
    public function getColumnsSchema(string $db, string $table): array
    {
        $query   = Query::table('information_schema.`columns`')->where('TABLE_SCHEMA', $db)->where('TABLE_NAME', $table);
        $columns = [
            'COLUMN_NAME'              => 'name',
            'DATA_TYPE'                => 'type',
            'COLUMN_DEFAULT'           => 'default',
            'COLUMN_KEY'               => 'key',
            'IS_NULLABLE'              => 'nullable',
            'COLUMN_TYPE'              => 'columnType',
            'COLUMN_COMMENT'           => 'columnComment',
            'CHARACTER_MAXIMUM_LENGTH' => 'length',
        ];

        return $query->get($columns)->getResult();
    }
}