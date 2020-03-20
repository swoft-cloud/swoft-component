<?php declare(strict_types=1);

namespace SwoftTest\Validator\Unit;

use Swoft\Validator\Validator;
use Swoft\Validator\Exception\ValidatorException;
use SwoftTest\Validator\Testing\ValidatorRequired;

class RequiredTest extends TestCase
{
    public function testRequiredType()
    {
        // 断言异常出现
        $data = [];
        $message = '';
        $validator = new Validator();
        try {
            $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
            $validator->validateRequest($data, $validates);
        } catch (ValidatorException $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('required must exist!', $message);

        // 断言异常不出现
        $data = ['required'=>''];
        $message = '';
        try {
            $validates = $this->getValidates(ValidatorRequired::class, 'testRequired');
            $validator->validateRequest($data, $validates);
        } catch (ValidatorException $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('', $message);
    }
}
