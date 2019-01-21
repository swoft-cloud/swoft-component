<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\PointBean;

/**
 * Class PointBeanParser
 *
 * @AnnotationParser(PointBean::class)
 *
 * @since 2.0
 */
class PointBeanParser extends Parser
{
    /**
     * Parse `PointBean` annotation
     *
     * @param int       $type
     * @param PointBean $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}