<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;

/**
 * Class PointAnnotationParser
 *
 * @AnnotationParser(PointAnnotation::class)
 *
 * @since 2.0
 */
class PointAnnotationParser extends Parser
{
    /**
     * @param int             $type
     * @param PointAnnotation $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}