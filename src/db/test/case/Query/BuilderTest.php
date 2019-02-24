<?php declare(strict_types=1);


namespace SwoftTest\Db\Query;


use Swoft\Db\DB;
use SwoftTest\Db\TestCase;

/**
 * Class BuilderTest
 *
 * @since 2.0
 */
class BuilderTest extends TestCase
{
    public function testSelect()
    {
        $sql = DB::pool()->table('user')->select(['id', 'name'])->toSql();

        var_dump($sql);
    }
}