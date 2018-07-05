<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest;

use Swoft\Bean\Annotation\CustomValidator;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Collector\ValidatorCollector;
use Swoft\Exception\ValidatorException;
use Swoft\Validator\ValidatorInterface;
use SwoftTest\Bean\ValidatorBean;
use SwoftTest\Validator\Annotation\TestAnnotation;
use SwoftTest\Validator\TestMaxValidator;
use SwoftTest\Validator\TestValidator;

/**
 * Class ValidatorTest
 *
 * @package Swoft\Test\Cases
 */
class ValidatorTest extends AbstractTestCase
{
    public function testCustomValidator()
    {
        // 将Validator载入ValidatorCollector
        $integerAnnotation = new Integer(['name' => 'id', 'max' => 100]);
        ValidatorCollector::collect(ValidatorBean::class, $integerAnnotation, 'id', 'method');
        $validatorAnnotation = new CustomValidator(['name' => 'name', 'validator' => TestValidator::class]);
        ValidatorCollector::collect(ValidatorBean::class, $validatorAnnotation, 'name', 'method');
        $validatorAnnotation = new CustomValidator(['name' => 'tpl', 'template' => '参数错误', 'validator' => TestValidator::class]);
        ValidatorCollector::collect(ValidatorBean::class, $validatorAnnotation, 'tpl', 'method');
        $validatorAnnotation = new TestAnnotation(['name' => 'max', 'max' => 10, 'template' => '名字太长了', 'validator' => TestMaxValidator::class]);
        ValidatorCollector::collect(ValidatorBean::class, $validatorAnnotation, 'max', 'method');

        $collector = ValidatorCollector::getCollector();

        // 测试Integer Validator
        $res = $collector[ValidatorBean::class]['method']['validator']['post']['id'];
        $validator = $res['validator'];
        list($min, $max, $throw, $tpl) = $res['params'];
        $params = ['id', 1, $min, $max, $throw, $tpl];
        /** @var ValidatorInterface $validator */
        $validator = bean($validator);
        $res = $validator->validate(...$params);
        $this->assertEquals(1, $res);

        // 测试自定义 Validator
        $res = $collector[ValidatorBean::class]['method']['validator']['post']['name'];
        $validator = $res['validator'];
        list($annotation) = $res['params'];
        /** @var ValidatorInterface $validator */
        $validator = bean($validator);

        // 验证参数合法
        $params = ['name', 'limx', $annotation];
        $res = $validator->validate(...$params);
        $this->assertEquals('limx', $res);

        // 验证参数不合法时，抛出异常
        $params = ['name', 'Agnes', $annotation];
        try {
            $validator->validate(...$params);
        } catch (ValidatorException $ex) {
            $this->assertEquals('Parameter name must be passed', $ex->getMessage());
        }

        // 验证参数不合法时，返回false
        $annotation->setThrow(false);
        $params = ['name', 'Agnes', $annotation];
        $res = $validator->validate(...$params);
        $this->assertFalse($res);

        // 验证自定义异常文案
        $res = $collector[ValidatorBean::class]['method']['validator']['post']['tpl'];
        $validator = $res['validator'];
        list($annotation) = $res['params'];
        /** @var ValidatorInterface $validator */
        $validator = bean($validator);
        $params = ['tpl', 'Agnes', $annotation];
        try {
            $validator->validate(...$params);
        } catch (ValidatorException $ex) {
            $this->assertEquals('参数错误', $ex->getMessage());
        }

        // 验证自定义验证器注解
        $res = $collector[ValidatorBean::class]['method']['validator']['post']['max'];
        $validator = $res['validator'];
        list($annotation) = $res['params'];
        /** @var ValidatorInterface $validator */
        $validator = bean($validator);
        $params = ['name', 'Agnes', $annotation];
        $res = $validator->validate(...$params);
        $this->assertEquals('Agnes', $res);

        $params = ['name', 'hi, swoft is a good framework', $annotation];
        try {
            $validator->validate(...$params);
        } catch (ValidatorException $ex) {
            $this->assertEquals('名字太长了', $ex->getMessage());
        }
    }
}
