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
use SwoftTest\Validator\Testing\ValidatorRule;

class ValidatorRuleTest extends TestCase
{
    public function testAfterDateError(): void
    {
        $data = [
            'dataAfterDate' => '2019-07-06'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('dataAfterDate must be after 2019-07-08');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testAfterDate'));
    }

    public function testAfterDateSuccess(): void
    {
        $data = [
            'dataAfterDate' => '2019-07-09 00:00:00'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testAfterDate')
        );
        $this->assertEquals($data, $result);
    }

    public function testAlphaError(): void
    {
        $data = [
            'dataAlpha' => 'abcde0123'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('alpha message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testAlpha'));
    }

    public function testAlphaSuccess(): void
    {
        $data = [
            'dataAlpha' => 'abcd'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testAlpha')
        );
        $this->assertEquals($data, $result);
    }

    public function testAlphaDashError(): void
    {
        $data = [
            'dataAlphaDash' => '.='
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('alphadash message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testAlphaDash'));
    }

    public function testAlphaDashSuccess(): void
    {
        $data = [
            'dataAlphaDash' => 'abcd0123-_'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testAlphaDash')
        );
        $this->assertEquals($data, $result);
    }

    public function testAlphaNumError(): void
    {
        $data = [
            'dataAlphaNum' => 'abcde-'
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('alphanum message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testAlphaNum'));
    }

    public function testAlphaNumSuccess(): void
    {
        $data = [
            'dataAlphaNum' => 'abcd012'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testAlphaNum')
        );
        $this->assertEquals($data, $result);
    }

    public function testBeforeDateError(): void
    {
        $data = [
            'dataBeforeDate' => '2019-07-10'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('before date message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testBeforeDate'));
    }

    public function testBeforeDateSuccess(): void
    {
        $data = [
            'dataBeforeDate' => '2019-07-01 00:00:00'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testBeforeDate')
        );
        $this->assertEquals($data, $result);
    }

    public function testChsError(): void
    {
        $data = [
            'dataChs' => 'english'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('chs message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testChs'));
    }

    public function testChsSuccess(): void
    {
        $data = [
            'dataChs' => '中文'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testChs')
        );
        $this->assertEquals($data, $result);
    }

    public function testChsAlphaError(): void
    {
        $data = [
            'dataChsAlpha' => '-_'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('chsalpha message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testChsAlpha'));
    }

    public function testChsAlphaSuccess(): void
    {
        $data = [
            'dataChsAlpha' => '中文english'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testChsAlpha')
        );
        $this->assertEquals($data, $result);
    }

    public function testChsAlphaDashError(): void
    {
        $data = [
            'dataChsAlphaDash' => '>?'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('chsalphadash message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testChsAlphaDash'));
    }

    public function testChsAlphaDashSuccess(): void
    {
        $data = [
            'dataChsAlphaDash' => '中文english0123-_'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testChsAlphaDash')
        );
        $this->assertEquals($data, $result);
    }

    public function testChsAlphaNumError(): void
    {
        $data = [
            'dataChsAlphaNum' => '-_'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('chsalphanum message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testChsAlphaNum'));
    }

    public function testChsAlphaNumSuccess(): void
    {
        $data = [
            'dataChsAlphaNum' => '中文english0123'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testChsAlphaNum')
        );
        $this->assertEquals($data, $result);
    }

    public function testConfirmError(): void
    {
        $data = [
            'dataConfirm' => '123',
            'confirm' => '456'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('confirm message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testConfirm'));
    }

    public function testConfirmSuccess(): void
    {
        $data = [
            'dataConfirm' => '123',
            'confirm' => '123'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testConfirm')
        );
        $this->assertEquals($data, $result);
    }

    public function testDifferentError(): void
    {
        $data = [
            'dataDifferent' => '123',
            'different' => '123'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('different message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testDifferent'));
    }

    public function testDifferentSuccess(): void
    {
        $data = [
            'dataDifferent' => '123',
            'different' => '1234a'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testDifferent')
        );
        $this->assertEquals($data, $result);
    }

    public function testGreaterThanError(): void
    {
        $data = [
            'dataGreaterThan' => '12',
            'gt' => '123'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('greaterthan message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testGreaterThan'));
    }

    public function testGreaterThanSuccess(): void
    {
        $data = [
            'dataGreaterThan' => '124',
            'gt' => '123'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testGreaterThan')
        );
        $this->assertEquals($data, $result);
    }

    public function testLessThanError(): void
    {
        $data = [
            'dataLessThan' => '124',
            'lt' => '123'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('lessthan message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testLessThan'));
    }

    public function testLessThanSuccess(): void
    {
        $data = [
            'dataLessThan' => '122',
            'lt' => '123'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testLessThan')
        );
        $this->assertEquals($data, $result);
    }

    public function testDateError(): void
    {
        $data = [
            'dataDate' => '2019f'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('date message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testDate'));
    }

    public function testDateSuccess(): void
    {
        $data = [
            'dataDate' => '2019-07-08 12:00:30'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testDate')
        );
        $this->assertEquals($data, $result);
    }

    public function testDateRangeError(): void
    {
        $data = [
            'dataDateRange' => '2019-06-18'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('daterange message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testDateRange'));
    }

    public function testDateRangeSuccess(): void
    {
        $data = [
            'dataDateRange' => '2019-07-07 00:00:00'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testDateRange')
        );

        $this->assertEquals($data, $result);
    }

    public function testDnsError(): void
    {
        $data = [
            'dataDns' => 'swoft.con'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('dns message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testDns'));
    }

    public function testDnsSuccess(): void
    {
        $data = [
            'dataDns' => 'baidu.com'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testDns')
        );
        $this->assertEquals($data, $result);
    }

//    public function testFileMediaTypeError()
//    {
//    }
//
//    public function testFileMediaTypeSuccess()
//    {
//    }
//
//    public function testFileSizeError()
//    {
//    }
//
//    public function testFileSizeSuccess()
//    {
//    }
//
//    public function testFileSuffixError()
//    {
//    }
//
//    public function testFileSuffixSuccess()
//    {
//    }
//    public function testIsFileError()
//    {
//    }
//    public function testIsFileSuccess()
//    {
//    }

    public function testLowError(): void
    {
        $data = [
            'dataLow' => 'swofT'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('low message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testLow'));
    }

    public function testLowSuccess(): void
    {
        $data = [
            'dataLow' => 'swoft'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testLow')
        );
        $this->assertEquals($data, $result);
    }

    public function testNotInEnumError(): void
    {
        $data = [
            'dataNotInEnum' => '1'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('notinenum message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testNotInEnum'));
    }

    public function testNotInEnumSuccess(): void
    {
        $data = [
            'dataNotInEnum' => '4'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testNotInEnum')
        );
        $this->assertEquals($data, $result);
    }

    public function testNotInRangeError(): void
    {
        $data = [
            'dataNotInRange' => '1'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('notinrange message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testNotInRange'));
    }

    public function testNotInRangeSuccess(): void
    {
        $data = [
            'dataNotInRange' => '4'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testNotInRange')
        );
        $this->assertEquals($data, $result);
    }

    public function testUpperError(): void
    {
        $data = [
            'dataUpper' => 'sWOFT'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('upper message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testUpper'));
    }

    public function testUpperSuccess(): void
    {
        $data = [
            'dataUpper' => 'SWOFT'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testUpper')
        );
        $this->assertEquals($data, $result);
    }

    public function testUrlError(): void
    {
        $data = [
            'dataUrl' => 'baidu.com'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('url message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidatorRule::class, 'testUrl'));
    }

    public function testUrlSuccess(): void
    {
        $data = [
            'dataUrl' => 'http://baidu.com'
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidatorRule::class, 'testUrl')
        );
        $this->assertEquals($data, $result);
    }
}
