<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit;


use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Exception\EloquentException;
use Swoft\Db\Exception\EntityException;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
use SwoftTest\Db\Testing\Entity\User;
use Swoole\Event;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Wait event
     */
    public function tearDown(): void
    {
        Event::wait();
    }

    /**
     * @return int
     * @throws ContainerException
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws QueryException
     * @throws ReflectionException
     * @throws DbException
     */
    public function addRecord(): int
    {
        /* @var User $user */
        $user = User::new();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        // Save result
        $result = $user->save();
        $this->assertTrue($result);

        return $user->getId();
    }
}
