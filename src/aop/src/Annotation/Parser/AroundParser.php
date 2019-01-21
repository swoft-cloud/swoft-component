<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\Around;

/**
 * Class AroundParser
 *
 * @AnnotationParser(Around::class)
 *
 * @since 2.0
 */
class AroundParser extends Parser
{
    /**
     * Parse `Around` annotation
     *
     * @param int    $type
     * @param Around $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}