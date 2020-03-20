<?php declare(strict_types=1);

namespace SwoftTest\Validator\Unit;

use Swoft\Validator\Validator;
use Swoft\Validator\Exception\ValidatorException;
use SwoftTest\Validator\Testing\ValidatorRequired;

class RequiredTest extends TestCase
{
    /**
     * @expectedException \Swoft\Validator\Exception\ValidatorException
     */
    public function testRequiredFailed()
    {
        // 断言异常出现
        $validator = new Validator();
        $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
        $validator->validateRequest([], $validates);
    }

    public function testRequiredPassed()
    {
        // 断言异常不出现
        $this->expectOutputString('successful');
        $validator = new Validator();
        $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
        $validator->validateRequest(['required'=>''], $validates);

        echo 'successful';
    }
}
