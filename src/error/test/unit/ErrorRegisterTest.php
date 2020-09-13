<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\ErrorHandler;

use PHPUnit\Framework\TestCase;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorRegister;
use SwoftTest\Error\Testing\CustomErrorHandler;
use SwoftTest\Error\Testing\ErrorTestException;
use function bean;

/**
 * Class ErrorRegisterTest
 */
class ErrorRegisterTest extends TestCase
{
    public function testBasic(): void
    {
        ErrorRegister::add(CustomErrorHandler::class, [ErrorTestException::class]);
        ErrorRegister::register($em = bean(ErrorManager::class));

        $this->assertTrue($em->getCount() > 0);

        $e = new ErrorTestException('test error');

        $handler = $em->matchHandler($e);
        $this->assertNotEmpty($handler);
    }
}
