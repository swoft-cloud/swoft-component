<?php declare(strict_types=1);


namespace SwoftTest\Annotation;

use SwoftTest\Annotation\Annotation\Mapping\Demo;
use SwoftTest\Annotation\Annotation\Mapping\DemoMethod;
use SwoftTest\Annotation\Annotation\Mapping\DemoProperty;

/**
 * Class DemoAnnotation
 *
 * @since 2.0
 *
 * @Demo(name="demoAnnotation")
 */
class DemoAnnotation
{
    /**
     * @DemoProperty(name="prop")
     *
     * @var string
     */
    private $prop = '';

    /**
     * @DemoProperty("defaultProp")
     *
     * @var string
     */
    private $defaultProp = '';

    /**
     * @DemoMethod(name="method")
     */
    public function method(): void
    {

    }

    /**
     * @DemoMethod("defaultMethod")
     */
    public function defaultMethod(): void
    {

    }
}