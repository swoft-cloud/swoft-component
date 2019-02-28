<?php

namespace Swoft\Annotation\Resource;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\AnnotationRegister;
use Swoft\Annotation\LoaderInterface;
use Swoft\Helper\CLog;
use Swoft\Stdlib\Helper\ComposerHelper;
use Swoft\Stdlib\Helper\DirectoryHelper;

/**
 * Annotation resource
 *
 * @since 2.0
 */
class AnnotationResource extends Resource
{
    /**
     * Default excluded psr4 prefixes
     */
    public const DEFAULT_EXCLUDED_PSR4_PREFIXES = [
        'Psr\\',
        'PHPUnit\\',
        'Symfony\\'
    ];

    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * @var string
     */
    private $loaderClassSuffix = 'php';

    /**
     * @var string
     */
    private $loaderClassName = 'AutoLoader';

    /**
     * Skip listed file names when parsing annotations
     *
     * @var array
     */
    private $excludedFilenames = [
        'Swoft.php' => 1,
    ];

    /**
     * Scans containing these namespace prefixes will be excluded
     *
     * @var array
     * eg. ['Psr\\', 'PHPUnit\\', 'Symfony\\']
     */
    private $excludedPsr4Prefixes;

    /**
     * AnnotationResource constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->registerLoader();
        $this->classLoader = ComposerHelper::getClassLoader();

        // init $excludedPsr4Prefixes
        $this->excludedPsr4Prefixes = self::DEFAULT_EXCLUDED_PSR4_PREFIXES;
    }

    /**
     * Load annotation resource
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function load(): void
    {
        $prefixDirsPsr4 = $this->classLoader->getPrefixesPsr4();

        foreach ($prefixDirsPsr4 as $ns => $paths) {
            // is excluded psr4 prefix
            if ($this->isExcludedPsr4Prefix($ns)) {
                CLog::info('Exclude %s', $ns);
                continue;
            }

            CLog::info('Scan %s', $ns);

            // find package/component loader class
            foreach ($paths as $path) {
                $annotationLoaderFile = $this->getAnnoationClassLoaderFile($path);
                if (!\file_exists($annotationLoaderFile)) {
                    continue;
                }

                CLog::info('Load %s', $annotationLoaderFile);

                $loaderClass = $this->getAnnotationLoaderClassName($ns);
                if (!\class_exists($loaderClass)) {
                    CLog::info('Auto loader(%s) is not exist class', $loaderClass);
                    continue;
                }

                $annotationLoader = new $loaderClass();
                if ($annotationLoader instanceof LoaderInterface) {
                    $this->loadAnnotation($annotationLoader);
                }

                // Register auto loader
                AnnotationRegister::addAutoLoader($ns, $annotationLoader);
            }
        }
    }

    /**
     * @param string $namespace
     *
     * @return bool
     */
    public function isExcludedPsr4Prefix(string $namespace): bool
    {
        foreach ($this->excludedPsr4Prefixes as $prefix) {
            if (0 === \strpos($namespace, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Load annotations
     *
     * @param LoaderInterface $loader
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    private function loadAnnotation(LoaderInterface $loader): void
    {
        $nsPaths = $loader->getPrefixDirs();
        foreach ($nsPaths as $ns => $path) {
            $iterator = DirectoryHelper::iterator($path);

            /* @var \SplFileInfo $splFileInfo */
            foreach ($iterator as $splFileInfo) {
                $pathName = $splFileInfo->getPathname();

                if (\is_dir($pathName)) {
                    continue;
                }

                $fileName  = $splFileInfo->getFilename();
                $extension = $splFileInfo->getExtension();

                if ($this->loaderClassSuffix !== $extension || \strpos($fileName, '.') === 0) {
                    continue;
                }

                // is exclude filename
                if (isset($this->excludedFilenames[$fileName])) {
                    continue;
                }

                $suffix        = sprintf('.%s', $this->loaderClassSuffix);
                $classPathName = str_replace([$path, '/'], ['', '\\'], $pathName);
                $classPathName = trim($classPathName, $suffix);

                $className = sprintf('%s%s', $ns, $classPathName);

                // Fix repeated autoloaded, such as `Swoft`
                if (!\class_exists($className)) {
                    continue;
                }

                // Parse annotation
                $this->parseAnnotation($ns, $className);
            }
        }
    }

    /**
     * @return ClassLoader
     */
    public function getClassLoader(): ClassLoader
    {
        return $this->classLoader;
    }

    /**
     * @param ClassLoader $classLoader
     */
    public function setClassLoader(ClassLoader $classLoader): void
    {
        $this->classLoader = $classLoader;
    }

    /**
     * @return string
     */
    public function getLoaderClassName(): string
    {
        return $this->loaderClassName;
    }

    /**
     * @param string $loaderClassName
     */
    public function setLoaderClassName(string $loaderClassName): void
    {
        $this->loaderClassName = $loaderClassName;
    }

    /**
     * Parser annotation
     *
     * @param string $namespace
     * @param string $className
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    private function parseAnnotation(string $namespace, string $className): void
    {
        // Annotation reader
        $reflectionClass    = new \ReflectionClass($className);
        $oneClassAnnotation = $this->parseOneClassAnnotation($reflectionClass);

        if (!empty($oneClassAnnotation)) {
            AnnotationRegister::registerAnnotation($namespace, $className, $oneClassAnnotation);
        }
    }

    /**
     * Parse class annotation
     *
     * @param \ReflectionClass $reflectionClass
     *
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    private function parseOneClassAnnotation(\ReflectionClass $reflectionClass): array
    {
        // Annotation reader
        $reader    = new AnnotationReader();
        $className = $reflectionClass->getName();

        $oneClassAnnotation = [];
        $classAnnotations   = $reader->getClassAnnotations($reflectionClass);

        // Register annotation parser
        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof AnnotationParser) {
                $this->registerParser($className, $classAnnotation);

                return [];
            }
        }

        // Class annotation
        if (!empty($classAnnotations)) {
            $oneClassAnnotation['annotation'] = $classAnnotations;
            $oneClassAnnotation['reflection'] = $reflectionClass;
        }

        // Property annotation
        $reflectionProperties = $reflectionClass->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName        = $reflectionProperty->getName();
            $propertyAnnotations = $reader->getPropertyAnnotations($reflectionProperty);

            if (!empty($propertyAnnotations)) {
                $oneClassAnnotation['properties'][$propertyName]['annotation'] = $propertyAnnotations;
                $oneClassAnnotation['properties'][$propertyName]['reflection'] = $reflectionProperty;
            }
        }

        // Method annotation
        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod) {
            $methodName        = $reflectionMethod->getName();
            $methodAnnotations = $reader->getMethodAnnotations($reflectionMethod);

            if (!empty($methodAnnotations)) {
                $oneClassAnnotation['methods'][$methodName]['annotation'] = $methodAnnotations;
                $oneClassAnnotation['methods'][$methodName]['reflection'] = $reflectionMethod;
            }
        }

        $parentReflectionClass = $reflectionClass->getParentClass();
        if ($parentReflectionClass !== false) {
            $parentClassAnnotation = $this->parseOneClassAnnotation($parentReflectionClass);
            if (!empty($parentClassAnnotation)) {
                $oneClassAnnotation['parent'] = $parentClassAnnotation;
            }
        }

        return $oneClassAnnotation;
    }

    /**
     * Register annotation parser
     *
     * @param string           $parserClassName
     * @param AnnotationParser $annotationParser
     */
    private function registerParser(string $parserClassName, AnnotationParser $annotationParser): void
    {
        $annotationClass = $annotationParser->getAnnotation();
        AnnotationRegister::registerParser($annotationClass, $parserClassName);
    }

    /**
     * Register annotation loader
     */
    private function registerLoader(): void
    {
        AnnotationRegistry::registerLoader(function (string $class) {
            if (\class_exists($class)) {
                return true;
            }

            return false;
        });
    }

    /**
     * Get annotation loader file
     *
     * @param string $path
     *
     * @return string
     */
    private function getAnnoationClassLoaderFile(string $path): string
    {
        return sprintf('%s/%s.%s', $path, $this->loaderClassName, $this->loaderClassSuffix);
    }

    /**
     * Get the class name of annotation loader
     *
     * @param string $namespace
     *
     * @return string
     */
    private function getAnnotationLoaderClassName(string $namespace): string
    {
        return \sprintf('%s%s', $namespace, $this->loaderClassName);
    }

    /**
     * @return array
     */
    public function getExcludedPsr4Prefixes(): array
    {
        return $this->excludedPsr4Prefixes;
    }

    /**
     * @param array $excludedPsr4Prefixes
     */
    public function setExcludedPsr4Prefixes(array $excludedPsr4Prefixes): void
    {
        $this->excludedPsr4Prefixes = $excludedPsr4Prefixes;
    }
}
