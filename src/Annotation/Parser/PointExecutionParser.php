<?php

namespace Swoft\Aop\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Aop\Annotation\Mapping\PointExecution;

/**
 * Class PointExecutionParser
 *
 * @AnnotationParser(PointExecution::class)
 *
 * @since 2.0
 */
class PointExecutionParser extends Parser
{
    /**
     * Parse `PointExecution` annotation
     *
     * @param int            $type
     * @param PointExecution $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
    }
}