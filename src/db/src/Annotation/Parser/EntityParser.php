<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Parser;


use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Db\Annotation\Mapping\Entity;

/**
 * Class EntityParser
 *
 * @since 2.0
 */
class EntityParser extends Parser
{
    /**
     * @param int    $type
     * @param Entity $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}