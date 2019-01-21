<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\After;

/**
 * Class AfterParser
 *
 * @AnnotationParser(After::class)
 *
 * @since 2.0
 */
class AfterParser extends Parser
{
    /**
     * Parse `After` annotation
     *
     * @param int   $type
     * @param After $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}