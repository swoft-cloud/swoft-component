<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit\Transaction;


use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Exception\EloquentException;
use Swoft\Db\Exception\EntityException;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
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
     * @throws ContainerException
     * @throws DbException
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws QueryException
     * @throws ReflectionException
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
     * @throws ContainerException
     * @throws DbException
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws QueryException
     * @throws ReflectionException
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
     * @throws ContainerException
     * @throws DbException
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws QueryException
     * @throws ReflectionException
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
     * @throws ContainerException
     * @throws DbException
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws QueryException
     * @throws ReflectionException
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
