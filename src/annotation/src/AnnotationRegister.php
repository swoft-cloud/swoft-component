<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Annotation;

use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;
use Swoft\Annotation\Contract\LoaderInterface;
use Swoft\Annotation\Resource\AnnotationResource;

/**
 * Class AnnotationRegister
 *
 * @since 2.0.0
 */
final class AnnotationRegister
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
     * All activity autoLoaders
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
     * All disabled autoLoaders
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
    private static $disabledLoaders = [];

    /**
     * @var array
     *
     * @example
     * [
     *     'namespace',
     *     'namespace2',
     * ]
     */
    private static $excludeNamespaces = [];

    /**
     * @var array
     *
     * @example
     * [
     *     '/xx/xxAutoLoaderFile',
     *     'AutoLoaderFile2',
     *     'AutoLoaderFile3',
     * ]
     */
    private static $autoLoaderFiles = [];

    /**
     * @var array
     *
     * @example
     * [
     *     'xxx.php'
     * ]
     */
    private static $excludeFilenames = [];

    /**
     * Annotation scan stats
     *
     * @var array
     */
    private static $classStats = [
        'parser'         => 0,
        'annotation'     => 0,
        'autoloader'     => 0,
        'disabledLoader' => 0,
    ];

    /**
     * Load annotation class
     *
     * @param array $config
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public static function load(array $config = []): void
    {
        $resource = new AnnotationResource($config);
        $resource->load();
    }

    /**
     * @param string $loadNamespace
     * @param string $className
     * @param array  $classAnnotation
     */
    public static function registerAnnotation(string $loadNamespace, string $className, array $classAnnotation): void
    {
        self::$classStats['annotation']++;
        self::$annotations[$loadNamespace][$className] = $classAnnotation;
    }

    /**
     * @param string $annotationClass
     * @param string $parserClassName
     */
    public static function registerParser(string $annotationClass, string $parserClassName): void
    {
        self::$classStats['parser']++;
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
     * @param string $ns
     */
    public static function addExcludeNamespace(string $ns): void
    {
        self::$excludeNamespaces[] = $ns;
    }

    /**
     * @param string $filename
     */
    public static function addExcludeFilename(string $filename): void
    {
        self::$excludeFilenames[] = $filename;
    }

    /**
     * @return array
     */
    public static function getClassStats(): array
    {
        return self::$classStats;
    }

    /**
     * @return array
     */
    public static function getExcludeNamespaces(): array
    {
        return self::$excludeNamespaces;
    }

    /**
     * @return array
     */
    public static function getExcludeFilenames(): array
    {
        return self::$excludeFilenames;
    }

    /**********************************************************************
     * AutoLoader manage
     *********************************************************************/

    /**
     * Add autoloader
     *
     * @param string          $namespace
     * @param LoaderInterface $loader
     * @param bool            $enable
     *
     * @return void
     */
    public static function addAutoLoader(string $namespace, LoaderInterface $loader, bool $enable): void
    {
        // Is an enabled
        if ($enable) {
            self::$classStats['autoloader']++;
            self::$autoLoaders[$namespace] = $loader;
            return;
        }

        self::$classStats['disabledLoader']++;
        self::$disabledLoaders[$namespace] = $loader;
    }

    /**
     * @return LoaderInterface[]
     */
    public static function getAutoLoaders(): array
    {
        return self::$autoLoaders;
    }

    /**
     * @return LoaderInterface[]
     */
    public static function getDisabledLoaders(): array
    {
        return self::$disabledLoaders;
    }

    /**
     * @param string $namespace
     *
     * @return LoaderInterface|null
     */
    public static function getAutoLoader(string $namespace): ?LoaderInterface
    {
        return self::$autoLoaders[$namespace] ?? null;
    }

    /**
     * @param string $namespace
     *
     * @return LoaderInterface|null
     */
    public static function getDisabledLoader(string $namespace): ?LoaderInterface
    {
        return self::$disabledLoaders[$namespace] ?? null;
    }

    /**
     * @param string $file
     */
    public static function addAutoLoaderFile(string $file): void
    {
        self::$autoLoaderFiles[] = $file;
    }

    /**
     * @return array
     */
    public static function getAutoLoaderFiles(): array
    {
        return self::$autoLoaderFiles;
    }
}
