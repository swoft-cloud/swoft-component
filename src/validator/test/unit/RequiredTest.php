<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Unit;

use Swoft\Validator\Validator;
use SwoftTest\Validator\Testing\ValidatorRequired;

class RequiredTest extends TestCase
{
    public function testRequiredFailed(): void
    {
        // 断言异常出现
        $validator = new Validator();
        $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
        $this->expectException(\Swoft\Validator\Exception\ValidatorException::class);
        $validator->validateRequest([], $validates);
    }

    public function testRequiredPassed(): void
    {
        // 断言异常不出现
        $this->expectOutputString('successful');
        $validator = new Validator();
        $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
        $validator->validateRequest(['required'=>''], $validates);

        echo 'successful';
    }
}
