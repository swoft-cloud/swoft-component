<?php

namespace Swoft\Bean\Definition\Parser;

use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Annotation\Parser\ParserInterface;
use Swoft\Bean\Definition\MethodInjection;
use Swoft\Bean\Definition\ObjectDefinition;
use Swoft\Bean\Definition\PropertyInjection;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class AnnotationParser
 *
 * @since 2.0
 */
class AnnotationObjParser extends ObjectParser
{
    /**
     * All load annotations
     *
     * @var array
     *
     * @example
     * [
     *    'loadNamespace' => [
     *        'className' => [
     *             'annotation' => [
     *                  new ClassAnnotation(),
     *                  new ClassAnnotation(),
     *                  new ClassAnnotation(),
     *             ]
     *             'reflection' => new ReflectionClass(),
     *             'properties' => [
     *                  'propertyName' => [
     *                      'annotation' => [
     *                          new PropertyAnnotation(),
     *                          new PropertyAnnotation(),
     *                          new PropertyAnnotation(),
     *                      ]
     *                     'reflection' => new ReflectionProperty(),
     *                  ]
     *             ],
     *            'methods' => [
     *                  'methodName' => [
     *                      'annotation' => [
     *                          new MethodAnnotation(),
     *                          new MethodAnnotation(),
     *                          new MethodAnnotation(),
     *                      ]
     *                     'reflection' => new ReflectionFunctionAbstract(),
     *                  ]
     *            ],
     *           'pathName' => '/xxx/xx/xx.php'
     *        ]
     *    ]
     * ]
     */
    private $annotations = [];

    /**
     * Annotation parser
     *
     * @var array
     *
     * @example
     * [
     *    'annotationClassName' => 'annotationParserClassName',
     * ]
     */
    private $parsers = [];

    /**
     * Parse annotations
     *
     * @param array $annotations
     * @param array $parsers
     *
     * @return array
     * @throws ContainerException
     */
    public function parseAnnotations(array $annotations, array $parsers): array
    {
        $this->parsers     = $parsers;
        $this->annotations = $annotations;

        foreach ($this->annotations as $loadNameSpace => $classes) {
            foreach ($classes as $className => $classOneAnnotations) {
                $this->parseOneClassAnnotations($className, $classOneAnnotations);
            }
        }

        return [$this->definitions, $this->objectDefinitions];
    }

    /**
     * Parse class all annotations
     *
     * @param string $className
     * @param array  $classOneAnnotations
     *
     * @throws ContainerException
     */
    private function parseOneClassAnnotations(string $className, array $classOneAnnotations): void
    {
        // Parse class annotations
        $classAnnotations = $classOneAnnotations['annotation'] ?? [];
        $reflectionClass  = $classOneAnnotations['reflection'];

        $classAry         = [
            $className,
            $reflectionClass,
            $classAnnotations
        ];
        $objectDefinition = $this->parseClassAnnotations($classAry);

        // Parse property annotations
        $propertyInjects        = [];
        $propertyAllAnnotations = $classOneAnnotations['properties'] ?? [];
        foreach ($propertyAllAnnotations as $propertyName => $propertyOneAnnotations) {
            $proAnnotatios  = $propertyOneAnnotations['annotation'] ?? [];
            $propertyInject = $this->parsePropertyAnnotations($classAry, $propertyName, $proAnnotatios);
            if (!empty($propertyInject)) {
                $propertyInjects[$propertyName] = $propertyInject;
            }
        }

        // Parse method annotations
        $methodInjects        = [];
        $methodAllAnnotations = $classOneAnnotations['methods'] ?? [];
        foreach ($methodAllAnnotations as $methodName => $methodOneAnnotations) {
            $methodAnnotations = $methodOneAnnotations['annotation'] ?? [];

            $methodInject = $this->parseMethodAnnotations($classAry, $methodName, $methodAnnotations);
            if (!empty($methodInject)) {
                $methodInjects[$methodName] = $methodInject;
            }
        }

        if (empty($objectDefinition)) {
            return;
        }

        if (!empty($propertyInjects)) {
            $objectDefinition->setPropertyInjections($propertyInjects);
        }

        if (!empty($methodInjects)) {
            $objectDefinition->setMethodInjections($methodInjects);
        }

        $name = $objectDefinition->getName();

        $this->objectDefinitions[$name] = $objectDefinition;
    }

    /**
     * @param array $classAry
     *
     * @return ObjectDefinition|null
     * @throws ContainerException
     */
    private function parseClassAnnotations(array $classAry): ?ObjectDefinition
    {
        list(, , $classAnnotations) = $classAry;

        $objectDefinition = null;
        foreach ($classAnnotations as $annotation) {
            $annotationClass = get_class($annotation);
            if (!isset($this->parsers[$annotationClass])) {
                continue;
            }

            $parserClassName  = $this->parsers[$annotationClass];
            $annotationParser = $this->getAnnotationParser($classAry, $parserClassName);

            $data = $annotationParser->parse(Parser::TYPE_CLASS, $annotation);
            if (empty($data)) {
                continue;
            }

            if (count($data) != 4) {
                throw new ContainerException('Return array with class annotation parse must be 4 size');
            }

            list($name, $className, $scope, $alias) = $data;
            $name = empty($name) ? $className : $name;

            if (empty($className)) {
                throw new ContainerException('Return array paramter with class name can not be empty');
            }

            // Multiple coverage
            $objectDefinition = new ObjectDefinition($name, $className, $scope, $alias);
        }

        return $objectDefinition;
    }

    /**
     * @param array  $classAry
     * @param string $propertyName
     * @param array  $propertyAnnotations
     *
     * @return PropertyInjection|null
     * @throws ContainerException
     */
    private function parsePropertyAnnotations(
        array $classAry,
        string $propertyName,
        array $propertyAnnotations
    ): ?PropertyInjection {

        $propertyInjection = null;
        foreach ($propertyAnnotations as $propertyAnnotation) {
            $annotationClass = get_class($propertyAnnotation);
            if (!isset($this->parsers[$annotationClass])) {
                continue;
            }

            $parserClassName  = $this->parsers[$annotationClass];
            $annotationParser = $this->getAnnotationParser($classAry, $parserClassName);

            $annotationParser->setPropertyName($propertyName);
            $data = $annotationParser->parse(Parser::TYPE_PROPERTY, $propertyAnnotation);

            $definitions = $annotationParser->getDefinitions();
            if (!empty($definitions)) {
                $this->definitions = array_merge($this->definitions, $definitions);
            }

            if (empty($data)) {
                continue;
            }

            if (count($data) != 2) {
                throw new ContainerException('Return array with property annotation parse must be 2 size');
            }

            // Multiple coverage
            list($propertyValue, $isRef) = $data;
            $propertyInjection = new PropertyInjection($propertyName, $propertyValue, $isRef);
        }

        return $propertyInjection;
    }

    /**
     * Parse method annotations
     *
     * @param array  $classAry
     * @param string $methodName
     * @param array  $methodAnnotations
     *
     * @return MethodInjection|null
     */
    private function parseMethodAnnotations(
        array $classAry,
        string $methodName,
        array $methodAnnotations
    ): ?MethodInjection {
        $methodInject = null;

        foreach ($methodAnnotations as $methodAnnotation) {
            $annotationClass = get_class($methodAnnotation);
            if (!isset($this->parsers[$annotationClass])) {
                continue;
            }

            $parserClassName  = $this->parsers[$annotationClass];
            $annotationParser = $this->getAnnotationParser($classAry, $parserClassName);

            $annotationParser->setMethodName($methodName);
            $data = $annotationParser->parse(Parser::TYPE_METHOD, $methodAnnotation);

            $definitions = $annotationParser->getDefinitions();
            if (!empty($definitions)) {
                $this->definitions = array_merge($this->definitions, $definitions);
            }

            if (empty($data)) {
                continue;
            }
        }

        return $methodInject;
    }

    /**
     * Get annotation parser
     *
     * @param array  $classAry
     * @param string $parserClassName
     *
     * @return ParserInterface
     */
    private function getAnnotationParser(array $classAry, string $parserClassName): ParserInterface
    {
        list($className, $reflectionClass, $classAnnotations) = $classAry;

        /* @var ParserInterface $annotationParser */
        $annotationParser = new $parserClassName($className, $reflectionClass, $classAnnotations);

        return $annotationParser;
    }
}