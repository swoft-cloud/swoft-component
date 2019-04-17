<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Query;


use Swoft\Db\DB;
use Swoft\Db\Query\Builder;
use SwoftTest\Db\Unit\TestCase;

/**
 * Class BuilderTest
 *
 * @since 2.0
 */
class BuilderTest extends TestCase
{
    public function testSelect()
    {
        $expectSql = 'select `id`, `name` from `user`';

        $sql  = DB::table('user')->select(...['id', 'name'])->toSql();
        $sql2 = DB::table('user')->select('id', 'name')->toSql();

        $this->assertEquals($expectSql, $sql);
        $this->assertEquals($expectSql, $sql2);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function testSelectSub()
    {
        $expectSql = 'select (select `id` from `count`) as `c` from `user`';

        $subSql = 'select `id` from `count`';
        $strSql = DB::table('user')->selectSub($subSql, 'c')->toSql();

        $subCb = function (Builder $query) {
            return $query->select('id')->from('count');
        };
        $cbSql = DB::table('user')->selectSub($subCb, 'c')->toSql();

        $builder  = Builder::new(DB::connection(), null, null)->from('count')->select('id');
        $buildSql = DB::table('user')->selectSub($builder, 'c')->toSql();

        $this->assertEquals($expectSql, $strSql);
        $this->assertEquals($expectSql, $cbSql);
        $this->assertEquals($expectSql, $buildSql);
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     */
    public function testSelectRaw()
    {
        $expectSql = 'select (select `id` from `count` where c=?) as c from `user`';
        $sql       = DB::table('user')->selectRaw('(select `id` from `count` where c=?) as c',
            [1])->toSql();

        $this->assertEquals($expectSql, $sql);
    }
}