<?php declare(strict_types=1);


namespace SwoftTest\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use SwoftTest\Annotation\Mapping\DemoMapping;

/**
 * Class DemoMappingParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=DemoMapping::class)
 */
class DemoMappingParser extends Parser
{
    /**
     * @param int    $type
     * @param object $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}