<?php declare(strict_types=1);


namespace Swoft\Task\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class TaskMappingParser
 *
 * @since 2.0
 *
 * @AnnotationParser(TaskMapping::class)
 */
class TaskMappingParser extends Parser
{
    /**
     * @param int         $type
     * @param TaskMapping $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {

        return [];
    }
}