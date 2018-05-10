<?php

namespace Swoft\Devtool\Model\Dao;

/**
 * SchemaInterface
 */
interface SchemaInterface
{
    /**
     * @param string $db
     * @param string $inc
     * @param string $exc
     *
     * @return array
     */
    public function getTableSchema(string $db, string $inc, string $exc): array;

    /**
     * @param string $db
     * @param string $table
     *
     * @return array
     */
    public function getColumnsSchema(string $db, string $table): array;
}