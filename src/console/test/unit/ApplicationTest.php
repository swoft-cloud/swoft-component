<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Console\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Console\Application;
use function bean;
use function input;

/**
 * Class ApplicationTest
 */
class ApplicationTest extends TestCase
{
    public function testBasic(): void
    {
        $app = bean('cliApp');

        $this->assertNotEmpty($app);
        $this->assertInstanceOf(Application::class, $app);

        $app->setName('cli app');
        $this->assertSame('cli app', $app->getName());

        $app->setVersion('1.0.0');
        $this->assertSame('1.0.0', $app->getVersion());

        $app->setDescription('cli desc');
        $this->assertSame('Cli desc', $app->getDescription());

        $this->assertEmpty($app->getCommentsVars());
        $app->setCommentsVars(['newKey' => 'new val']);
        $this->assertIsArray($vars = $app->getCommentsVars());
        $this->assertArrayHasKey('newKey', $vars);
        $this->assertArrayNotHasKey('workDir', $vars);
    }

    public function testRun(): void
    {
        $app = bean('cliApp');

        $input = input();
        $input->setFlags([
            '--comm1', 'v1',
            '--comm2', 'v2',
            'a1'
        ]);
        $input->setCommand('demo:sub');

        $app->run();

        $this->assertIsArray($vars = $app->getCommentsVars());
    }
}
