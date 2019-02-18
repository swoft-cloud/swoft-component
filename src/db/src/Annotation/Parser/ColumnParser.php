<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;

/**
 * Class ColumnParser
 *
 * @AnnotationParser(Column::class)
 * @since 2.0
 */
class ColumnParser extends Parser
{
    /**
     * @param int    $type
     * @param Column $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}