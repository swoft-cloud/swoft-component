<?php declare(strict_types=1);


namespace SwoftTest\Db\Eloquent;


use Swoft\Bean\BeanFactory;
use SwoftTest\Db\TestCase;

class ModelTest extends TestCase
{
    public function testMethod()
    {
        var_dump(BeanFactory::getBean('config'));
        $this->assertTrue(true);
    }
}