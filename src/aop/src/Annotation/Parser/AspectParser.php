<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\Aspect;

/**
 * Class AspectParser
 *
 * @AnnotationParser(Aspect::class)
 *
 * @since 2.0
 */
class AspectParser extends Parser
{
    /**
     * Parse `Aspect` annotation
     *
     * @param int    $type
     * @param Aspect $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}