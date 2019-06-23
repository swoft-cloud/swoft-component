<?php declare(strict_types=1);


namespace Swoft\Bean\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Primary;

/**
 * Class PrimaryParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=Primary::class)
 */
class PrimaryParser extends Parser
{
    /**
     * @param int     $type
     * @param Primary $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}