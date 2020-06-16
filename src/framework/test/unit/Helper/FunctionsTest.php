<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;
use function alias;
use function env;
use function putenv;

class FunctionsTest extends TestCase
{
    use CommonTestAssertTrait;

    public function testEnv(): void
    {
        // set var
        putenv('TEST_PHP_HOME=/php/home');
        $this->assertSame('/php/home', env('TEST_PHP_HOME'));
        $this->assertNotEmpty(env());

        $tests = [
            // bool: true
            'on'      => true,
            'yes'     => true,
            'true'    => true,
            '(true)'  => true,
            // bool: false
            'off'     => false,
            'no'      => false,
            'false'   => false,
            '(false)' => false,
            // null
            'null'    => null,
            '(null)'  => null,
        ];
        foreach ($tests as $val => $want) {
            putenv('TEST_PHP_HOME=' . $val);
            $this->assertSame($want, env('TEST_PHP_HOME'));
        }

        // unset var
        putenv('TEST_PHP_HOME');
        $this->assertNull(env('TEST_PHP_HOME'));
        $this->assertSame('def', env('TEST_PHP_HOME', 'def'));
    }

    public function testAlias(): void
    {
        $this->assertSame('', alias(''));
        $this->assertSame('invalid', alias('invalid'));

        $this->assetExceptionWithMessage(function () {
            alias('@notExist');
        }, 'The alias does not exist，alias=@notExist');

        $this->assetExceptionContainMessage(function () {
            alias('@notExist');
        }, 'alias=@notExist');

        Swoft::setAlias('testAlias', 'alias/value');
        $this->assertSame('testAlias', alias('testAlias'));
        $this->assertSame('alias/value', alias('@testAlias'));

        // remove
        Swoft::setAlias('@testAlias');
        $this->assetExceptionWithMessage(function () {
            alias('@testAlias');
        }, 'The alias does not exist，alias=@testAlias');
    }
}
