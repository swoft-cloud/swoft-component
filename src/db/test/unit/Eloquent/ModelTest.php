<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Eloquent;


use Swoft\Db\DB;
use SwoftTest\Db\Testing\Entity\User;
use SwoftTest\Db\Unit\TestCase;
use Swoole\Event;

/**
 * Class ModelTest
 *
 * @since 2.0
 */
class ModelTest extends TestCase
{
    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testSave()
    {
        $user = User::new();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        // Save result
        $result = $user->save();
        $this->assertTrue($result);

        // Insert id

        $this->assertTrue($user->getId() >= 1);

        $user2 = User::new();
        $user2->setAge(100);

        // Save result
        $result2 = $user2->save();
        $this->assertTrue($result2);

        // Properties
        $attributes = [
            'name'      => uniqid(),
            'password'  => md5(uniqid()),
            'age'       => mt_rand(1, 100),
            'user_desc' => 'u desc'
        ];

        $result3 = User::new($attributes)->save();
        $this->assertTrue($result3);

        $user = User::create($attributes);
        $this->assertIsObject($user);
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testDelete()
    {
        $id   = $this->addRecord();
        $user = User::find($id);

        $result = $user->delete();
        $this->assertTrue($result);
    }

    public function testUpdateByWhere()
    {
        $res1 = User::updateOrCreate(['id' => 1], ['age' => 18, 'name' => 'sakuraovq']);

        $wheres   = [
            'name' => 'sakuraovq',
            ['id', '>=', 2]
        ];
        $orWheres = [
            ['name', 'like', '%s%']
        ];
        $result   = User::where($wheres)->orWhere($orWheres)->update(['name' => 'sakuraovq' . mt_rand(1, 10)]);
        $this->assertGreaterThan(0, $result);

        $updateBeforeAge = $res1->getAge();
        // update by id
        $updateByWhereId = User::where('id', 1)->increment('age', 1);
        $updateByModel   = User::find(1)->decrement('age', 2);
        $updateByModel2  = User::find(1)->increment('age', 1);

        $this->assertEquals(1, $updateByWhereId);
        $this->assertEquals(1, $updateByModel);
        $this->assertEquals(1, $updateByModel2);

        /* @var User $updateAfter */
        $updateAfter = User::find(1);
        $this->assertEquals($updateBeforeAge, $updateAfter->getAge());
    }

    public function testBatchDelete()
    {
        /* @var User $res1 */
        $res1 = User::updateOrCreate(['id' => 1], ['age' => 18]);

        $res2 = User::updateOrCreate(['id' => 2], ['age' => 18]);

        $result = User::whereIn('id', [$res1->getId(), $res2->getId()])->delete();
        $this->assertEquals(2, $result);


        /* @var User $res3 */
        $res3 = User::updateOrCreate(['id' => 5], ['age' => 18, 'name' => 'sakura']);

        $wheres    = [
            'age' => 18,
            ['id', '>=', 2]
        ];
        $orWheres  = [
            ['name', 'like', '%s%']
        ];
        $expectSql = 'select * from `user` where (`age` = ? and `id` >= ?) or (`name` like ?)';
        $this->assertEquals($expectSql, User::where($wheres)->orWhere($orWheres)->toSql());

        $resultDelete = User::where($wheres)->orWhere($orWheres)->delete();

        $this->assertGreaterThan(0, $resultDelete);
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testUpdate()
    {
        $id   = $this->addRecord();
        $user = User::find($id);

        $name   = uniqid();
        $result = $user->update(['name' => $name]);
        $this->assertTrue($result);

        /* @var User $user2 */
        $user2 = User::find($id);
        $this->assertEquals($name, $user2->getName());

        // Update
        $aName = uniqid();
        $aAge  = mt_rand(1, 100);

        /* @var User $aUser */
        $aUser = User::updateOrCreate(['name' => $aName], ['age' => $aAge]);

        $this->assertEquals($aUser->getName(), $aName);
        $this->assertEquals($aUser->getAge(), $aAge);

        // Create
        $uName = uniqid();
        $uAge  = mt_rand(1, 100);

        /* @var User $uUser */
        $uUser = User::updateOrCreate(['id' => $id], ['name' => $uName, 'age' => $uAge]);

        /* @var User $user */
        $user = User::find($id);
        $this->assertEquals($uName, $user->getName());
        $this->assertEquals($uAge, $user->getAge());

        $uiName = uniqid();
        $result = User::updateOrInsert(['id' => $id], ['name' => $uiName]);

        $this->assertTrue($result);

        /* @var User $user */
        $user = User::find($id);
        $this->assertEquals($uiName, $user->getName());
    }

    public function testModelSelect()
    {
        \sgo(function () {
            // Delete only left 20 rows
            $resCount = DB::selectOne('select count(*) as `count` from `user`')->count;
            DB::delete('delete A FROM `user` A INNER JOIN (SELECT ID FROM `user` B limit ?) B
on A.id=B.id;', [$resCount - 20]);

            $afterCount = DB::selectOne('select count(*) as `count` from `user`')->count;
            $this->assertEquals(20, $afterCount);
        });

        foreach (User::query()->cursor() as $user) {
            /* @var User $user */
            $this->assertGreaterThan(0, $user->getId());
        }

        // query all, each 10 strips
        User::query()->chunk(10, function ($users) {
            /* @var User $user */
            foreach ($users as $user) {
                //var_dump($user->getId());
            }
        });
    }
}
