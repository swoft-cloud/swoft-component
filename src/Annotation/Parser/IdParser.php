<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Db\Annotation\Mapping\Id;

/**
 * Class IdParser
 *
 * @AnnotationParser(Id::class)
 * @since 2.0
 */
class IdParser extends Parser
{
    /**
     * @param int $type
     * @param Id  $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}