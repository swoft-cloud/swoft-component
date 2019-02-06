<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;


use Swoft\Annotation\Annotation\Parser\Parser;

/**
 * Class ColumnParser
 *
 * @since 2.0
 */
class ColumnParser extends Parser
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