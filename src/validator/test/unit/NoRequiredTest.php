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

use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Validator;
use SwoftTest\Validator\Testing\ValidatorNoRequired;

class NoRequiredTest extends TestCase
{
    public function testNoRequiredType(): void
    {
        $data = [];
        try {
            [$result] = (new Validator())->validateRequest(
                $data,
                $this->getValidates(ValidatorNoRequired::class, 'testNoRequired')
            );
        } catch (ValidatorException $e) {
        }
        
        $this->assertEmpty($result);
        $this->assertIsArray($result);
        $this->assertEquals($data, $result);
    }
}
