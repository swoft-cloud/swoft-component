<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Config\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Config\Annotation\Mapping\Config;
use function sprintf;

/**
 * Class ConfigParser
 *
 * @AnnotationParser(Config::class)
 */
class ConfigParser extends Parser
{
    /**
     * Parse config annotation
     *
     * @param int    $type
     * @param Config $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== Parser::TYPE_PROPERTY) {
            return [];
        }

        $key   = $annotationObject->getKey();
        $value = sprintf('.config.%s', $key);

        return [$value, true];
    }
}
