<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Router\RouteRegister;

/**
 * Class RequestMappingParser
 *
 * @since 2.0
 *
 * @AnnotationParser(RequestMapping::class)
 */
class RequestMappingParser extends Parser
{
    /**
     * @param int            $type
     * @param RequestMapping $annotation
     *
     * @return array
     * @throws AnnotationException
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@RequestMapping` must be defined on class method!');
        }

        $routeInfo = [
            'action' => $this->methodName,
            'route'  => $annotation->getRoute(),
            'name'   => $annotation->getName(),
            'method' => $annotation->getMethod(),
            'params' => $annotation->getParams(),
        ];

        // Add route info for controller action
        RouteRegister::addRoute($this->className, $routeInfo);

        return [];
    }
}
