<?php

namespace Swoft\Http\Server\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Http\Server\Annotation\Mapping\Controller;

/**
 * Class ControllerParser
 *
 * @AnnotationParser(Controller::class)
 *
 * @since 2.0
 */
class ControllerParser extends Parser
{
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}