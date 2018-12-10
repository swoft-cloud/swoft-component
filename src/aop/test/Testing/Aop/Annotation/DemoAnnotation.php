<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Aop\Testing\Aop\Annotation;

/**
 * Class DemoAnnotation
 * @Annotation
 * @Target("METHOD")
 */
class DemoAnnotation
{
    /**
     * @var string
     */
    private $name;

    public function __construct(array $values)
    {
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
