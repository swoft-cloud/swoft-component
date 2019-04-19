<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Query;

use Swoft\Bean\Exception\ContainerException;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Db\DB;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Query\Builder;
use Swoft\Db\Query\Expression;
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
     * @throws ContainerException
     * @throws PrototypeException
     * @throws PoolException
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

        $builder  = Builder::new()->from('count')->select('id');
        $buildSql = DB::table('user')->selectSub($builder, 'c')->toSql();

        $this->assertEquals($expectSql, $strSql);
        $this->assertEquals($expectSql, $cbSql);
        $this->assertEquals($expectSql, $buildSql);
    }

    /**
     * @throws PrototypeException
     */
    public function testSelectRaw()
    {
        $expectSql = 'select (select `id` from `count` where c=?) as c from `user`';
        $sql       = DB::table('user')->selectRaw('(select `id` from `count` where c=?) as c',
            [1])->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    public function testSelectBetween()
    {
        $expectSql = 'select `age` from `user` where `age` between ? and ?';
        $db        = DB::table('user')->select('age')->whereBetween('age', [18, 25]);
        $sql       = $db->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    /**
     * @throws ContainerException
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function testSelectExpression()
    {
        $expectSql = 'select * from `user` where (`age` > 18 and age < 35) or `age` < 18';
        $sql       = DB::table('user')
            ->where(function (Builder $builder) {
                $builder->where('age', '>', Expression::new('18 and age < 35'));
            })
            ->orWhere('age', '<', Expression::new('18'))
            ->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    /**
     * @throws PrototypeException
     */
    public function testSelectLeftJoin()
    {
        $expectSql = 'select * from `count` left join `user` as `u` on `u`.`id` = `count`.`user_id`';
        $sql       = DB::table('count')
            ->join('user as u', 'u.id', '=', "count.user_id", 'left')
            ->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    /**
     * @throws ContainerException
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function testSelectSubJoinQuery()
    {
        $expectSql = 'select * from `count` inner join (' .
            'select `age` from `user` where `age` between ? and ?' .
            ') as `user` on `user`.`id` = `count`.`user_id` where `id` < ?';

        // Example1
        $user = DB::table('user')->select('age')->whereBetween('age', [18, 25]);
        $sql  = DB::table('count')
            ->where('id', '<', 200)
            ->joinSub($user, 'user', 'user.id', '=', "count.user_id")
            ->toSql();

        // Example2
        $user1 = 'select `age` from `user` where `age` between ? and ?';
        $sql1  = DB::table('count')
            ->where('id', '<', 200)
            ->joinSub($user1, 'user', 'user.id', '=', "count.user_id")
            ->toSql();

        $this->assertEquals($expectSql, $sql1);
        $this->assertEquals($expectSql, $sql);
    }

    /**
     * @throws ContainerException
     * @throws PrototypeException
     * @throws \ReflectionException
     */
    public function testSelectList()
    {
        $expectSql = 'select * from `count` where `name` like ? and `id` > ? and (`age` = ?) ' .
            'or `user_desc` between ? and ? ' .
            'and (exists (select `user_id` from `count` where `user_id` between ? and ?))';
        $where     = [
            'age' => 18
        ];
        $sql       = DB::table('count')
            ->where('name', "like", "lit%")
            ->where('id', '>', 1000)
            ->where($where)
            ->whereBetween('user_desc', [1, 5], 'or', false)
            ->where(function (Builder $builder) {
                $count = DB::table('count')->select('user_id')->whereBetween('user_id', [1, 5]);
                $builder->addWhereExistsQuery($count);
            })
            ->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    public function testHaving()
    {
        $expectSql = 'select `sum(age)` as `sum_age`, `count(user_desc)` as `count_desc`, `id` from `user` ' .
            'group by `user_desc` ' .
            'having `count_desc` = ? or `sum_age` > ?';

        $db = DB::table('user')
            ->select('sum(age) as sum_age')
            ->addSelect(['count(user_desc) as count_desc', 'id'])
            ->groupBy('user_desc');

        $sql1 = (clone $db)
            ->having('count_desc', "1")
            ->having('sum_age', '>', "1", 'or')
            ->toSql();
        $sql2 = (clone $db)
            ->having('count_desc', "1")
            ->orHaving('sum_age', '>', "1")
            ->toSql();
        $sql3 = (clone $db)
            ->having('count_desc', "1")
            ->havingRaw("`sum_age` > ?", ["1"], 'or')
            ->toSql();

        $this->assertEquals($expectSql, $sql1);
        $this->assertEquals($expectSql, $sql2);
        $this->assertEquals($expectSql, $sql3);
    }

    public function testDistinct()
    {
        $expectSql = 'select distinct * from `user`';
        $sql       = DB::table('user')->distinct()->toSql();
        $this->assertEquals($expectSql, $sql);
    }

}
