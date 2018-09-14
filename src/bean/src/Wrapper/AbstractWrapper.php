<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Wrapper;

use Swoft\Bean\ObjectDefinition;
use Swoft\Bean\ObjectDefinition\PropertyInjection;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\Bean\Parser\MethodWithoutAnnotationParser;
use Swoft\Bean\Resource\AnnotationResource;
use Swoft\Bean\Wrapper\Extend\WrapperExtendInterface;
use function array_merge;
use function basename;
use function dirname;
use function get_class;
use function in_array;
use function str_replace;

abstract class AbstractWrapper implements WrapperInterface
{
    /**
     * 类注解
     */
    protected $classAnnotations = [];

    /**
     * 属性注解
     */
    protected $propertyAnnotations = [];

    /**
     * 方法注解
     */
    protected $methodAnnotations = [];

    /**
     * 注解资源
     *
     * @var AnnotationResource
     */
    protected $annotationResource;

    /**
     * @var WrapperExtendInterface[]
     */
    protected $extends = [];

    public function __construct(AnnotationResource $annotationResource)
    {
        $this->annotationResource = $annotationResource;
    }

    /**
     * 封装注解
     *
     * @throws \ReflectionException if the class does not exist.
     */
    public function doWrapper(string $className, array $annotations): array
    {
        $reflectionClass = new \ReflectionClass($className);

        // Resolve class level annotation
        $beanDefinition = $this->parseClassAnnotations($className, $annotations['class']);

        // No cofiguration for inject
        if (empty($beanDefinition) && ! $reflectionClass->isInterface()) {
            // Resolve property
            $properties = $reflectionClass->getProperties();
            $propertyAnnotations = $annotations['property'] ?? [];
            $this->parseProperties($propertyAnnotations, $properties, $className);

            // Resolve method
            $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methodAnnotations = $annotations['method'] ?? [];

            $this->parseMethods($methodAnnotations, $className, $publicMethods);

            return [];
        }


        // Parser bean annotation
        list($beanName, $scope, $ref) = $beanDefinition;

        // Init object
        $objectDefinition = new ObjectDefinition();
        $objectDefinition->setName($beanName);
        $objectDefinition->setClassName($className);
        $objectDefinition->setScope($scope);
        $objectDefinition->setRef($ref);

        if (! $reflectionClass->isInterface()) {
            // Resolve property
            $propertyAnnotations = $annotations['property'] ?? [];
            $propertyInjections = $this->parseProperties($propertyAnnotations, $reflectionClass->getProperties(), $className);
            $objectDefinition->setPropertyInjections($propertyInjections);
        }

        // Resolve method
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodAnnotations = $annotations['method'] ?? [];
        $this->parseMethods($methodAnnotations, $className, $publicMethods);

        return [$beanName, $objectDefinition];
    }

    /**
     * @return array|null
     */
    public function parseClassAnnotations(string $className, array $annotations)
    {
        if (! $this->isParseClass($annotations)) {
            return null;
        }

        $beanData = null;
        foreach ($annotations as $annotation) {
            $annotationClass = get_class($annotation);
            if (! in_array($annotationClass, $this->getClassAnnotations(), false)) {
                continue;
            }

            // annotation parser
            $annotationParser = $this->getAnnotationParser($annotation);
            if ($annotationParser === null) {
                continue;
            }
            $annotationData = $annotationParser->parser($className, $annotation);
            if ($annotationData !== null) {
                $beanData = $annotationData;
            }
        }

        return $beanData;
    }

    public function addExtends(WrapperExtendInterface $extend)
    {
        $extendClass = get_class($extend);
        $this->extends[$extendClass] = $extend;
    }

    protected function inMethodAnnotations($methodAnnotation): bool
    {
        $annotationClass = get_class($methodAnnotation);
        return in_array($annotationClass, $this->getMethodAnnotations(), false);
    }

    private function parseProperties(array $propertyAnnotations, array $properties, string $className): array
    {
        $propertyInjections = [];

        /* @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            if (! isset($propertyAnnotations[$propertyName]) || ! $this->isParseProperty($propertyAnnotations[$propertyName])) {
                continue;
            }

            $object = new $className();
            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);

            list($injectProperty, $isRef) = $this->parsePropertyAnnotations($propertyAnnotations, $className, $propertyName, $propertyValue);
            if ($injectProperty === null) {
                continue;
            }

            $propertyInjection = new PropertyInjection($propertyName, $injectProperty, (bool)$isRef);
            $propertyInjections[$propertyName] = $propertyInjection;
        }

        return $propertyInjections;
    }

    private function parseMethods(array $methodAnnotations, string $className, array $publicMethods)
    {
        // 循环解析
        foreach ($publicMethods as $method) {
            /* @var \ReflectionMethod $method */
            if ($method->isStatic()) {
                continue;
            }

            /* @var \ReflectionClass $declaredClass */
            $declaredClass = $method->getDeclaringClass();
            $declaredName = $declaredClass->getName();

            // 不是当前类方法
            if ($declaredName !== $className) {
                continue;
            }
            $this->parseMethodAnnotations($className, $method, $methodAnnotations);
        }
    }

    private function parseMethodAnnotations(string $className, \ReflectionMethod $method, array $methodAnnotations)
    {
        // 方法没有注解解析
        $methodName = $method->getName();
        $isWithoutMethodAnnotation = empty($methodAnnotations) || ! isset($methodAnnotations[$methodName]);
        if ($isWithoutMethodAnnotation || ! $this->isParseMethod($methodAnnotations[$methodName])) {
            $this->parseMethodWithoutAnnotation($className, $methodName);
            return;
        }

        // 循环方法注解解析
        foreach ($methodAnnotations[$methodName] ?? [] as $methodAnnotationAry) {
            foreach ($methodAnnotationAry ?? [] as $methodAnnotation) {
                if (! $this->inMethodAnnotations($methodAnnotation)) {
                    continue;
                }

                // 解析器解析
                $annotationParser = $this->getAnnotationParser($methodAnnotation);
                if ($annotationParser === null) {
                    $this->parseMethodWithoutAnnotation($className, $methodName);
                    continue;
                }
                $annotationParser->parser($className, $methodAnnotation, '', $methodName);
            }
        }
    }

    /**
     * 方法没有配置路由注解解析
     */
    private function parseMethodWithoutAnnotation(string $className, string $methodName)
    {
        $parser = new MethodWithoutAnnotationParser($this->annotationResource);
        $parser->parser($className, null, '', $methodName);
    }

    /**
     * 属性解析
     */
    private function parsePropertyAnnotations(
        array $propertyAnnotations,
        string $className,
        string $propertyName,
        $propertyValue
    ): array {
        $isRef = false;
        $injectProperty = '';

        // No annotations
        if (empty($propertyAnnotations) || ! isset($propertyAnnotations[$propertyName]) || ! $this->isParseProperty($propertyAnnotations[$propertyName])) {
            return [null, false];
        }

        // Resolve property annotation
        foreach ($propertyAnnotations[$propertyName] ?? [] as $propertyAnnotation) {
            $annotationClass = get_class($propertyAnnotation);
            if (! in_array($annotationClass, $this->getPropertyAnnotations(), false)) {
                continue;
            }

            $annotationParser = $this->getAnnotationParser($propertyAnnotation);
            if ($annotationParser === null) {
                $injectProperty = null;
                $isRef = false;
                continue;
            }
            list($injectProperty, $isRef) = $annotationParser->parser($className, $propertyAnnotation, $propertyName, '', $propertyValue);
        }

        return [$injectProperty, $isRef];
    }

    private function getClassAnnotations(): array
    {
        return array_merge($this->classAnnotations, $this->getExtendAnnotations(1));
    }

    private function getPropertyAnnotations(): array
    {
        return array_merge($this->propertyAnnotations, $this->getExtendAnnotations(2));
    }

    private function getMethodAnnotations(): array
    {
        return array_merge($this->methodAnnotations, $this->getExtendAnnotations(3));
    }

    private function getExtendAnnotations(int $type = 1): array
    {
        $annotations = [];
        foreach ($this->extends as $extend) {
            if ($type === 1) {
                $extendAnnoation = $extend->getClassAnnotations();
            } elseif ($type === 2) {
                $extendAnnoation = $extend->getPropertyAnnotations();
            } else {
                $extendAnnoation = $extend->getMethodAnnotations();
            }
            $annotations = array_merge($annotations, $extendAnnoation);
        }

        return $annotations;
    }

    private function isParseClass(array $annotations): bool
    {
        return $this->isParseClassAnnotations($annotations) || $this->isParseExtendAnnotations($annotations, 1);
    }

    private function isParseProperty(array $annotations): bool
    {
        return $this->isParsePropertyAnnotations($annotations) || $this->isParseExtendAnnotations($annotations, 2);
    }

    private function isParseMethod(array $annotations): bool
    {
        return $this->isParseMethodAnnotations($annotations) || $this->isParseExtendAnnotations($annotations, 3);
    }

    private function isParseExtendAnnotations(array $annotations, int $type = 1): bool
    {
        foreach ($this->extends as $extend) {
            if ($type === 1) {
                $isParse = $extend->isParseClassAnnotations($annotations);
            } elseif ($type === 2) {
                $isParse = $extend->isParsePropertyAnnotations($annotations);
            } else {
                $isParse = $extend->isParseMethodAnnotations($annotations);
            }
            if ($isParse) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取注解对应解析器
     * @return AbstractParser|null
     */
    private function getAnnotationParser($objectAnnotation)
    {
        $annotationClassName = get_class($objectAnnotation);
        $classNameTmp = str_replace('\\', '/', $annotationClassName);
        $className = basename($classNameTmp);
        $namespaceDir = dirname($classNameTmp, 2);
        $namespace = str_replace('/', '\\', $namespaceDir);

        // 解析器类名
        $annotationParserClassName = "{$namespace}\\Parser\\{$className}Parser";
        if (! class_exists($annotationParserClassName)) {
            return null;
        }

        $annotationParser = new $annotationParserClassName($this->annotationResource);
        return $annotationParser;
    }
}
