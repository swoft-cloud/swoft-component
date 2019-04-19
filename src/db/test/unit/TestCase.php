<?php declare(strict_types=1);


namespace SwoftTest\Db\Unit;


use SwoftTest\Db\Testing\Entity\User;

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
        \Swoole\Event::wait();
    }

    /**
     * @return int
     * @throws \Swoft\Bean\Exception\PrototypeException
     * @throws \Swoft\Db\Exception\EloquentException
     * @throws \Swoft\Db\Exception\EntityException
     * @throws \Swoft\Db\Exception\PoolException
     * @throws \Swoft\Db\Exception\QueryException
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
