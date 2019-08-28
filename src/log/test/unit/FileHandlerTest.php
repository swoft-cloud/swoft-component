<?php declare(strict_types=1);


namespace SwoftTest\Log\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use Swoft\Log\Handler\FileHandler;

/**
 * Class FileHandlerTest
 *
 * @since 2.0
 */
class FileHandlerTest extends TestCase
{
    public function testFormatFile(): void
    {
        $result = $this->getHandler()->formatFile("notice-%d{Y-m-d}.log");
        $this->assertEquals($result, 'notice-' . date('Y-m-d') . '.log');

        $result = $this->getHandler()->formatFile("notice.log");
        $this->assertEquals($result, 'notice.log');

        $result = $this->getHandler()->formatFile("%d{Y-m-d}notice.log");
        $this->assertEquals($result, date('Y-m-d') . 'notice.log');

        $result = $this->getHandler()->formatFile("notice.log%d{Y-m-d}");
        $this->assertEquals($result, 'notice.log' . date('Y-m-d'));
    }

    private function getHandler(): FileHandler
    {
        return BeanFactory::getBean('testFileHandler');
    }
}