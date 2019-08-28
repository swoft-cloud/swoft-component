<?php declare(strict_types=1);

namespace SwoftTest\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Co;
use Swoft\Exception\SwoftException;
use Swoole\Coroutine\Context;

/**
 * Class CoroutineTest
 *
 * @since 2.0
 */
class CoroutineTest extends TestCase
{
    /**
     * @var int
     */
    private $create = 0;

    /**
     * Base
     */
    public function testBase(): void
    {
        $id = Co::id();
        $this->assertIsInt($id);
        $this->assertTrue($id > 0);

        $tid = Co::tid();
        $this->assertIsInt($tid);
        $this->assertTrue($tid > 0);

        $stats = Co::stats();
        $this->assertIsArray($stats);
        $this->assertTrue(Co::exists($id));
        $this->assertEquals(Co::getPcid(), -1);
        $this->assertInstanceOf(Context::class, Co::getContext($id));

        foreach (Co::list() as $key => $value) {
            $this->assertIsInt($value);
        }
    }

    public function createNotWait()
    {
        $current = 6;
        Co::create(function () use ($current) {
            $this->create = $current;
        }, false);

        Co::sleep(1);

        $this->assertEquals($this->create, $current);
    }

    /**
     * @throws SwoftException
     */
    public function testFile(): void
    {
        $data     = 'datas1tring';
        $fileName = __DIR__ . '/t.txt';

        Co::writeFile($fileName, $data);

        $result = Co::readFile($fileName);
        $this->assertEquals($result, $data);

        Co::exec('rm -rf ' . $fileName);
    }

    /**
     * @throws SwoftException
     */
    public function testGetHostByName(): void
    {
        $ip     = '39.106.56.0';
        $result = Co::getHostByName('www.swoft.org', 2);
        $this->assertEquals($result, $ip);

        $result = Co::getAddrInfo('www.swoft.org');
        $this->assertEquals($result, [$ip]);
    }
}
