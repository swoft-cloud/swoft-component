<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Eloquent;


use SwoftTest\Db\Testing\Entity\User;
use SwoftTest\Db\Unit\TestCase;

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
        $this->assertGreaterThan(1, $user->getId());

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
}
