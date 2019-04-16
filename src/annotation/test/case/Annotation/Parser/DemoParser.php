<?php declare(strict_types=1);


namespace SwoftTest\Annotation\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use SwoftTest\Annotation\Annotation\Mapping\Demo;

/**
 * Class DemoParser
 *
 * @since 2.0
 * @AnnotationParser(annotation=Demo::class)
 */
class DemoParser extends Parser
{
    /**
     * @param int  $type
     * @param Demo $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}