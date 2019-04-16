<?php declare(strict_types=1);


namespace SwoftTest\Annotation;


use PHPUnit\Framework\TestCase;
use Swoft\Annotation\AnnotationRegister;

class AnnotationTest extends TestCase
{
    public function testA()
    {
        var_dump(AnnotationRegister::getAnnotations());
        $this->assertTrue(true);
    }
}