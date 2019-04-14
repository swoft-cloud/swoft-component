<?php declare(strict_types=1);

namespace Swoft\Error\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ExceptionHandler
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"METHOD"})
 * @Attributes({
 *     @Attribute("exception", type="string")
 * })
 */
class ExceptionHandlerParser extends Parser
{
    /**
     * @var array
     * [
     *  handler class => [exception class, exception class1],
     * ]
     */
    private static $handlers = [];

    /**
     * Parse object
     *
     * @param int              $type       Class or Method or Property
     * @param ExceptionHandler $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@ExceptionHandler` must be defined on class!');
        }

        $handlerClass = $this->className;

        self::add($handlerClass, $annotation->getExceptions());

        return [$handlerClass, $handlerClass, Bean::SINGLETON, ''];
    }

    /**
     * @param string $handlerClass
     * @param array  $exceptions
     */
    public static function add(string $handlerClass, array $exceptions): void
    {
        self::$handlers[$handlerClass] = $exceptions;
    }

    /**
     * @return array
     */
    public static function getHandlers(): array
    {
        return self::$handlers;
    }

    /**
     * Clear data
     */
    public static function clear(): void
    {
        self::$handlers = [];
    }
}
