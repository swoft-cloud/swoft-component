<?php declare(strict_types=1);


namespace SwoftTest\Db;


use Swoft\Test\TestApplication;

/**
 * Class TestCase
 *
 * @since 2.0
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Wait event
     */
    public function tearDown()
    {
        \Swoole\Event::wait();
    }
}