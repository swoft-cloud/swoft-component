<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Annotation\Parser;

use ReflectionClass;
use ReflectionException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Swoft\Rpc\Server\Router\RouteRegister;

/**
 * Class ServiceParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=Service::class)
 */
class ServiceParser extends Parser
{
    /**
     * @param int     $type
     * @param Service $annotationObject
     *
     * @return array
     * @throws ReflectionException
     */
    public function parse(int $type, $annotationObject): array
    {
        $reflectionClass = new ReflectionClass($this->className);
        $interfaces      = $reflectionClass->getInterfaceNames();

        foreach ($interfaces as $interface) {
            RouteRegister::register($interface, $annotationObject->getVersion(), $this->className);
        }

        return [$this->className, $this->className, Bean::SINGLETON, ''];
    }
}
