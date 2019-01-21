<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\AfterReturning;

/**
 * Class AfterReturningParser
 *
 * @AnnotationParser(AfterReturning::class)
 * 
 * @since 2.0
 */
class AfterReturningParser extends Parser
{
    /**
     * Parse `AfterReturning` annotation
     *
     * @param int            $type
     * @param AfterReturning $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
    }
}