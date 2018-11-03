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
namespace Swoft\Bean\Resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Swoft\Bean\Wrapper\WrapperInterface;
use Swoft\Helper\ComposerHelper;
use function array_merge;
use function basename;
use function class_exists;
use function dirname;
use function get_class;
use function interface_exists;
use function is_string;
use function pathinfo;
use function str_replace;

abstract class AnnotationResource extends AbstractResource
{
    /**
     * The namespaces will be scan.
     */
    protected $scanNamespaces = [];

    /**
     * The files will be scan.
     */
    protected $scanFiles = [];

    /**
     * Resoloved bean definitions.
     *
     * @var array
     * <pre>
     * [
     *     'beanName' => ObjectDefinition,
     *      ...
     * ]
     * </pre>
     */
    protected $definitions = [];

    protected $annotations = [];

    protected $serverScan = [
        'Command',
        'Bootstrap',
        'Aop',
    ];

    /**
     * The name of console componet
     */
    protected $consoleName = 'console';

    /**
     * The namespace of components
     */
    protected $componentNamespaces = [];

    /**
     * The annotations that wanna ignore
     */
    protected $ignoredNames = [
        'Usage',
        'Options',
        'Arguments',
        'Example',
    ];

    /**
     * the custom components
     * @var array
     */
    protected $customComponents = [];

    /**
     * AnnotationResource constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
        if (isset($properties['components']['custom']) && is_array($properties['components']['custom'])) {
            $this->customComponents = $properties['components']['custom'];
        }
    }

    /**
     * 获取已解析的配置beans
     *
     * @return array
     * <pre>
     * [
     *     'beanName' => ObjectDefinition,
     *      ...
     * ]
     * </pre>
     */
    public function getDefinitions(): array
    {
        // 获取扫描的PHP文件
        $classNames = $this->registerLoaderAndScanBean();
        $fileClassNames = $this->scanFilePhpClass();
        $classNames = array_merge($classNames, $fileClassNames);

        foreach ($classNames as $className) {
            $this->parseBeanAnnotations($className);
        }
        $this->parseAnnotationsData();

        return $this->definitions;
    }

    public function parseBeanAnnotations(string $className)
    {
        if (! class_exists($className) && ! interface_exists($className)) {
            return null;
        }

        // 注解解析器
        $reader = new AnnotationReader();
        $reader = $this->addIgnoredNames($reader);
        $reflectionClass = new \ReflectionClass($className);
        $classAnnotations = $reader->getClassAnnotations($reflectionClass);

        // 没有类注解不解析其它注解
        if (empty($classAnnotations)) {
            return;
        }

        foreach ($classAnnotations as $classAnnotation) {
            $this->annotations[$className]['class'][get_class($classAnnotation)] = $classAnnotation;
        }

        // 解析属性
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            $propertyAnnotations = $reader->getPropertyAnnotations($property);
            foreach ($propertyAnnotations as $propertyAnnotation) {
                $this->annotations[$className]['property'][$propertyName][get_class($propertyAnnotation)] = $propertyAnnotation;
            }
        }

        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($publicMethods as $method) {
            if ($method->isStatic()) {
                continue;
            }

            $methodName = $method->getName();

            // 解析方法注解
            $methodAnnotations = $reader->getMethodAnnotations($method);

            foreach ($methodAnnotations as $methodAnnotation) {
                $this->annotations[$className]['method'][$methodName][get_class($methodAnnotation)][] = $methodAnnotation;
            }
        }
    }

    /**
     * 解析注解数据
     */
    public function parseAnnotationsData()
    {
        foreach ($this->annotations as $className => $annotation) {
            $classAnnotations = $annotation['class'];
            $this->parseClassAnnotations($className, $annotation, $classAnnotations);
        }
    }

    public function addScanNamespace(array $namespaces)
    {
        foreach ($namespaces as $key => $namespace) {
            if (is_string($key)) {
                $this->scanNamespaces[$key] = $namespace;
                continue;
            }
            $nsPath = ComposerHelper::getDirByNamespace($namespace);
            if (! $nsPath) {
                $nsPath = str_replace('\\', '/', $namespace);
                $nsPath = BASE_PATH . '/' . $nsPath;
            }
            $this->scanNamespaces[$namespace] = $nsPath;
        }

        $this->registerNamespace();
    }

    /**
     * Register namespace
     *
     * @return void
     */
    abstract public function registerNamespace();

    /**
     * 扫描目录下PHP文件
     */
    protected function scanPhpFile(string $dir, string $namespace): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $iterator = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($iterator);

        $phpFiles = [];
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $pathName = $file->getPathname();
            $fileType = pathinfo($pathName, PATHINFO_EXTENSION);
            if ($fileType != 'php') {
                continue;
            }

            $replaces = ['', '\\', '', ''];
            $searches = [$dir, '/', '.php', '.PHP'];

            $pathName = str_replace($searches, $replaces, $pathName);
            $phpFiles[] = $namespace . $pathName;
        }

        return $phpFiles;
    }

    /**
     * Scan files
     */
    protected function scanFilePhpClass()
    {
        $phpClass = [];
        foreach ($this->scanFiles as $ns => $files) {
            foreach ($files as $file) {
                $pathInfo = pathinfo($file);
                if (! isset($pathInfo['filename'])) {
                    continue;
                }
                $phpClass[] = $ns . '\\' . $pathInfo['filename'];
            }
        }

        return $phpClass;
    }

    /**
     * 注册加载器和扫描PHP文件
     */
    protected function registerLoaderAndScanBean(): array
    {
        $phpClass = [];
        foreach ($this->scanNamespaces as $namespace => $dir) {
            AnnotationRegistry::registerLoader(function ($class) {
                if (class_exists($class) || interface_exists($class)) {
                    return true;
                }

                return false;
            });
            $scanClass = $this->scanPhpFile($dir, $namespace);
            $phpClass = array_merge($phpClass, $scanClass);
        }

        return array_unique($phpClass);
    }

    /**
     * add ignored names
     */
    protected function addIgnoredNames(Reader $reader): Reader
    {
        foreach ($this->ignoredNames as $name) {
            $reader->addGlobalIgnoredName($name);
        }

        return $reader;
    }

    /**
     * 类注解封装
     */
    private function parseClassAnnotations(string $className, array $annotation, array $classAnnotations)
    {
        foreach ($classAnnotations as $classAnnotation) {
            $annotationClassName = get_class($classAnnotation);
            $classNameTmp = str_replace('\\', '/', $annotationClassName);
            $classFileName = basename($classNameTmp);
            $beanNamespaceTmp = dirname($classNameTmp, 2);
            $beanNamespace = str_replace('/', '\\', $beanNamespaceTmp);

            $annotationWrapperClassName = "{$beanNamespace}\\Wrapper\\{$classFileName}Wrapper";

            if (! class_exists($annotationWrapperClassName)) {
                continue;
            }

            /* @var WrapperInterface $wrapper */
            $wrapper = new $annotationWrapperClassName($this);

            // wrapper extend
            foreach ($this->componentNamespaces as $componentNamespace) {
                $annotationWrapperExtendClassName = "{$componentNamespace}\\Bean\\Wrapper\\Extend\\{$classFileName}Extend";
                if (! class_exists($annotationWrapperExtendClassName)) {
                    continue;
                }
                $extend = new $annotationWrapperExtendClassName();
                $wrapper->addExtends($extend);
            }

            $objectDefinitionAry = $wrapper->doWrapper($className, $annotation);
            if ($objectDefinitionAry) {
                list($beanName, $objectDefinition) = $objectDefinitionAry;
                $this->definitions[$beanName] = $objectDefinition;
            }
        }
    }

    public function getComponentNamespaces(): array
    {
        return $this->componentNamespaces;
    }
}
