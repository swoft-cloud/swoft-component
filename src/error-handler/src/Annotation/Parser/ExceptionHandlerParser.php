<?php declare(strict_types=1);

namespace Swoft\ErrorHandler\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\ErrorHandler\HandlerRegister;

/**
 * Class ExceptionHandler
 *
 * @since 2.0
 * @Annotation
 * @Target({"METHOD"})
 * @Attributes({
 *     @Attribute("exception", type="string")
 * })
 */
class ExceptionHandlerParser extends Parser
{
    /**
     * Parse object
     *
     * @param int    $type Class or Method or Property
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

        HandlerRegister::add($handlerClass, $annotation->getPriority(), $annotation->getExceptions());

        return [$handlerClass, $handlerClass, Bean::SINGLETON, ''];
    }
}
