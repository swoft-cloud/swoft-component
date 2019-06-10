<?php declare(strict_types=1);

namespace SwoftTest\Console\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Console\Application;
use function bean;

/**
 * Class ApplicationTest
 */
class ApplicationTest extends TestCase
{
    public function testRun(): void
    {
        $app = bean('cliApp');

        $this->assertNotEmpty($app);
        $this->assertInstanceOf(Application::class, $app);
    }
}
