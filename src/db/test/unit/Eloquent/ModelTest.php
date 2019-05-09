<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Eloquent;


use PhpParser\ErrorHandler\Collecting;
use Swoft\Db\DB;
use Swoft\Db\Eloquent\Collection;
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
        $user = new User;
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

        $batch = User::insert([
            [
                'name'      => uniqid(),
                'password'  => md5(uniqid()),
                'age'       => mt_rand(1, 100),
                'user_desc' => 'u desc'
            ],
            [
                'name'      => uniqid(),
                'password'  => md5(uniqid()),
                'age'       => mt_rand(1, 100),
                'user_desc' => 'u desc'
            ]
        ]);
        $this->assertTrue($batch);
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


        User::updateOrInsert(['id' => 1], ['age' => 18, 'name' => 'sakuraovq']);
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
        $uUser = User::updateOrCreate(['id' => 22], ['name' => "sakura", 'age' => 18]);

        $user = User::find(22);
        $user->addHidden(['age']);
        $user->setVisible(['password']);
        $user->addHidden(['password']);
        $user->addVisible(['age']);
        $user->addVisible(['pwd']);

        DB::transaction(function () {

        });
        $this->assertArrayHasKey('pwd', $user->toArray());
        // Delete only left 20 rows
        $resCount = DB::selectOne('select count(*) as `count` from `user`')->count;
        if ($resCount - 20 > 0) {
            DB::delete('delete A FROM `user` A INNER JOIN (SELECT ID FROM `user` B limit ?) B
on A.id=B.id;', [$resCount - 20]);
            $res = DB::selectOne('select count(*) as `count` from `user`')->count;
            $this->assertEquals(20, $res);
        }
        foreach (User::query()->cursor() as $user) {
            /* @var User $user */
            $this->assertGreaterThan(0, $user->getId());
        }

        // query all, each 10 strips
        User::query()->chunk(10, function ($users) {
            foreach ($users as $user) {
                /* @var User $user */
                $this->assertIsInt($user->getId());
            }
        });
    }

    public function testPick()
    {
        $result   = User::pluck('name', 'age');
        $dbResult = DB::table('user')->pluck('name', 'age');

        $this->assertEquals($result, $dbResult);

        foreach ($result as $age => $name) {
            $this->assertIsInt($age);
            $this->assertIsString($name);
        }
    }

    public function testImplode()
    {
        $ageString = DB::table('user')->where('age', '>', 18)->implode('age', ',');

        $this->assertEquals($ageString, User::where('age', '>', 18)->implode('age', ','));
    }

    public function testAggregate()
    {
        $result = DB::table('user')->max('age');;
        $this->assertEquals($result, User::query()->max('age'));

        $result1 = User::query()->min('age');
        $this->assertEquals($result1, DB::table('user')->min('age'));

        $result2 = User::query()->average('age');
        $this->assertEquals($result2, DB::table('user')->average('age'));

        $result3 = User::query()->avg('age');
        $this->assertEquals($result3, DB::table('user')->avg('age'));
        $this->assertEquals($result2, $result3);

        $result4 = User::query()->count();
        $this->assertEquals($result4, DB::table('user')->count());

        // sql = select max(`id`) as id from `user` group by user_desc
        $res = User::query()->selectRaw('max(`id`) as id')->groupBy('user_desc')->get();

        /* @var $v User */
        foreach ($res as $v) {
            $this->assertGreaterThan(0, $v->getId());
        }
        $ages = array_column($res->toArray(), 'id');
        $this->assertIsArray($ages);
    }

    public function testSkip()
    {

        $users = User::skip(10)->take(5)->get();

        foreach ($users as $user) {
            /* @var $user User */
            $this->assertIsInt($user->getId());
        }
        $this->assertInstanceOf('Swoft\Db\Eloquent\Collection', $users);
    }

    public function testWheres()
    {
        $expectSql = 'select * from `user` where (`name` = ? and `status` >= ? or `money` > ?)';

        $wheres = [
            'name' => 'sakuraovq',
            ['status', '>=', 2],
            ['money', '>', 0, 'or']
        ];
        $sql    = User::where($wheres)->toSql();

        $this->assertEquals($expectSql, $sql);
    }

    public function testValue()
    {
        $res = User::value('user_desc');

        $this->assertIsString($res);
    }

    public function testChunkId()
    {
        User::chunkById(2, function (Collection $users) {
            /* @var $user User */
            foreach ($users as $user) {
                $this->assertGreaterThan(0, $user->getId());
            }
            $res = $users->pluck('age', 'id')->toArray();
            $this->assertIsArray($res);
        });

    }
}
