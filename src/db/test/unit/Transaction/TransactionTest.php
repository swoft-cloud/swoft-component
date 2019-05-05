<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Transaction;


use Swoft\Db\DB;
use SwoftTest\Db\Testing\Entity\User;
use SwoftTest\Db\Unit\TestCase;

/**
 * Class TransactionTest
 *
 * @since 2.0
 */
class TransactionTest extends TestCase
{
    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testCommit()
    {
        $id  = $this->addRecord();
        $id2 = $this->addRecord();

        $name  = uniqid();
        $name2 = uniqid();

        DB::beginTransaction();
        $result = User::updateOrInsert(['id' => $id], ['name' => $name]);
        $this->assertTrue($result);

        $result2 = User::updateOrInsert(['id' => $id2], ['name' => $name2]);
        $this->assertTrue($result2);
        DB::commit();

        /* @var User $user */
        $user = User::find($id);

        /* @var User $user2 */
        $user2 = User::find($id2);

        $this->assertEquals($name, $user->getName());
        $this->assertEquals($name2, $user2->getName());
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testCommitByNest()
    {
        $id  = $this->addRecord();
        $id2 = $this->addRecord();

        $name  = uniqid();
        $name2 = uniqid();

        DB::beginTransaction();
        $result = User::updateOrInsert(['id' => $id], ['name' => $name]);
        $this->assertTrue($result);

        DB::beginTransaction();
        $result2 = User::updateOrInsert(['id' => $id2], ['name' => $name2]);
        $this->assertTrue($result2);
        DB::commit();

        DB::commit();

        /* @var User $user */
        $user = User::find($id);

        /* @var User $user2 */
        $user2 = User::find($id2);

        $this->assertEquals($name, $user->getName());
        $this->assertEquals($name2, $user2->getName());
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testRollback()
    {
        $id  = $this->addRecord();
        $id2 = $this->addRecord();

        $name  = uniqid();
        $name2 = uniqid();

        DB::beginTransaction();
        $result = User::updateOrInsert(['id' => $id], ['name' => $name]);
        $this->assertTrue($result);

        $result2 = User::updateOrInsert(['id' => $id2], ['name' => $name2]);
        $this->assertTrue($result2);
        DB::rollBack();

        /* @var User $user */
        $user = User::find($id);

        /* @var User $user2 */
        $user2 = User::find($id2);

        $this->assertNotEquals($name, $user->getName());
        $this->assertNotEquals($name2, $user2->getName());
    }

    /**
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
     */
    public function testRollbackByNest()
    {
        $id  = $this->addRecord();
        $id2 = $this->addRecord();

        $name  = uniqid();
        $name2 = uniqid();

        DB::beginTransaction();
        $result = User::updateOrInsert(['id' => $id], ['name' => $name]);
        $this->assertTrue($result);

        DB::beginTransaction();
        $result2 = User::updateOrInsert(['id' => $id2], ['name' => $name2]);
        $this->assertTrue($result2);
        DB::commit();

        DB::rollBack();

        /* @var User $user */
        $user = User::find($id);

        /* @var User $user2 */
        $user2 = User::find($id2);

        $this->assertNotEquals($name, $user->getName());
        $this->assertNotEquals($name2, $user2->getName());
    }

    public function testTransaction()
    {
        $id  = $this->addRecord();
        $id2 = $this->addRecord();

        DB::beginTransaction();
        $result = User::updateOrInsert(['id' => $id], ['name' => 'sakura1']);
        $this->assertTrue($result);
        $result2 = User::updateOrInsert(['id' => $id2], ['name' => 'sakura2']);
        $this->assertTrue($result2);
        DB::commit();
    }
}
