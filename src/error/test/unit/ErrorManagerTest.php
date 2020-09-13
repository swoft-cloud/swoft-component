<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Error\Unit;

use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Swoft\Error\DefaultExceptionHandler;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use SwoftTest\Error\Testing\CustomErrorHandler;

/**
 * Class ErrorManagerTest
 */
class ErrorManagerTest extends TestCase
{
    public function testGeneric(): void
    {
        $e  = new RuntimeException('error on runtime');
        $eh = new ErrorManager();

        // null
        $ret = $eh->match($e);
        $this->assertNull($ret);

        $eh->addHandler(RuntimeException::class, CustomErrorHandler::class);

        $this->assertTrue($eh->getCount() > 0);
        $this->assertTrue($eh->getTypeCount() > 0);

        $ret = $eh->match($e);
        $this->assertNotEmpty($ret);

        $e1  = new LogicException('logic error');
        $ret = $eh->match($e1);
        $this->assertNull($ret);

        $eh->clear();

        $this->assertSame(0, $eh->getCount());
    }

    public function testDefHandler(): void
    {
        $deh = new DefaultExceptionHandler();

        $this->assertSame(ErrorType::DEF, $deh->getType());
        $this->assertSame(ErrorType::DEFAULT, $deh->getType());
    }
}
