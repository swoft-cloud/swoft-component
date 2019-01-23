<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\AspectRegister;

/**
 * Class AfterThrowingParser
 *
 * @AnnotationParser(AfterThrowing::class)
 *
 * @since 2.0
 */
class AfterThrowingParser extends Parser
{
    /**
     * Parse `AfterThrowing` annotation
     *
     * @param int           $type
     * @param AfterThrowing $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_METHOD) {
            throw new AopException('`@AfterThrowing` must be defined by method!');
        }

        AspectRegister::registerAdvice(AspectRegister::ADVICE_AFTERTHROWING, $this->className, $this->methodName);

        return [];
    }
}