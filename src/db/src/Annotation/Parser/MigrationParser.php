<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;


use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Db\Annotation\Mapping\Migration;

/**
 * Class MigrationParser
 *
 * @since 2.0
 */
class MigrationParser extends Parser
{
    /**
     * @param int       $type
     * @param Migration $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}