<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Annotation\Testing;

use SwoftTest\Annotation\Testing\Annotation\Mapping\DemoClass;
use SwoftTest\Annotation\Testing\Annotation\Mapping\DemoMethod;
use SwoftTest\Annotation\Testing\Annotation\Mapping\DemoProperty;

/**
 * Class DemoAnnotation
 *
 * @since 2.0
 *
 * @DemoClass(name="demoAnnotation")
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
