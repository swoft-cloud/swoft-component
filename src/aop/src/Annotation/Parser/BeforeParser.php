<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\Before;

/**
 * Class BeforeParser
 *
 * @AnnotationParser(Before::class)
 *
 * @since 2.0
 */
class BeforeParser extends Parser
{
    /**
     * Parse `Before` annotation
     *
     * @param int    $type
     * @param Before $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}