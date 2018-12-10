<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
