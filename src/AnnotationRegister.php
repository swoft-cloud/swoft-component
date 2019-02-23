<?php

namespace Swoft\Annotation;

use Swoft\Annotation\Resource\AnnotationResource;

/**
 * Annotation register
 */
class AnnotationRegister
{
    /**
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
     *            ]
     *        ]
     *    ]
     * ]
     */
    private static $annotations = [];

    /**
     * Annotation parsers
     *
     * @var array
     *
     * @example
     * [
     *    'annotationClassName' => 'annotationParserClassName',
     * ]
     */
    private static $parsers = [];

    /**
     * All auto loaders
     *
     * @var LoaderInterface[]
     *
     * @example
     * [
     *     'namespace' => new AutoLoader(),
     *     'namespace' => new AutoLoader(),
     *     'namespace' => new AutoLoader(),
     * ]
     */
    private static $autoLoaders = [];

    /**
     * Load annotation class
     *
     * @throws \Exception
     */
    public static function load(): void
    {
        $resource = new AnnotationResource();
        $resource->load();
    }

    /**
     * @param string $loadNamespace
     * @param string $className
     * @param array  $classAnnotation
     */
    public static function registerAnnotation(string $loadNamespace, string $className, array $classAnnotation): void
    {
        self::$annotations[$loadNamespace][$className] = $classAnnotation;
    }

    /**
     * @param string $annotationClass
     * @param string $parserClassName
     */
    public static function registerParser(string $annotationClass, string $parserClassName): void
    {
        self::$parsers[$annotationClass] = $parserClassName;
    }

    /**
     * @return array
     */
    public static function getAnnotations(): array
    {
        return self::$annotations;
    }

    /**
     * @return array
     */
    public static function getParsers(): array
    {
        return self::$parsers;
    }

    /**
     * @return LoaderInterface[]
     */
    public static function getAutoLoaders(): array
    {
        return self::$autoLoaders;
    }

    /**
     * Add autoloader
     *
     * @param string          $namespace
     * @param LoaderInterface $autoLoader
     *
     * @return void
     */
    public static function addAutoLoader(string $namespace, LoaderInterface $autoLoader): void
    {
        self::$autoLoaders[$namespace] = $autoLoader;
    }

    /**
     * @param string $namespace
     * @return LoaderInterface|null
     */
    public static function getAutoLoader(string $namespace): ?LoaderInterface
    {
        return self::$autoLoaders[$namespace] ?? null;
    }
}
