<?php

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
