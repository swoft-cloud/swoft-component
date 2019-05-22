<?php declare(strict_types=1);

namespace Swoft\Bean\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class BeanParser
 *
 * @AnnotationParser(Bean::class)
 *
 * @since 2.0
 */
class BeanParser extends Parser
{
    /**
     * Parse object
     *
     * @param int  $type
     * @param Bean $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        // Only to parse class annotation with `@Bean`
        if ($type != self::TYPE_CLASS) {
            return [];
        }

        $name  = $annotationObject->getName();
        $scope = $annotationObject->getScope();
        $alias = $annotationObject->getAlias();

        return [$name, $this->className, $scope, $alias];
    }
}