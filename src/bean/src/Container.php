<?php

namespace Swoft\Bean;

use Psr\Container\ContainerInterface;
use Swoft\Aop\Aop;
use Swoft\Aop\Proxy\Proxy;
use Swoft\App;
use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\ObjectDefinition\ArgsInjection;
use Swoft\Bean\ObjectDefinition\MethodInjection;
use Swoft\Bean\ObjectDefinition\PropertyInjection;
use Swoft\Bean\Resource\DefinitionResource;
use Swoft\Bean\Resource\ServerAnnotationResource;
use Swoft\Bean\Resource\WorkerAnnotationResource;
use Swoft\Exception\ContainerException;

/**
 * Class Container
 *
 * @package Swoft\Bean
 */
class Container implements ContainerInterface
{
    /**
     * Map of entries with Singleton scope that are already resolved.
     *
     * @var array
     */
    private $singletonEntries = [];

    /**
     * 已解析的bean规则
     *
     * @var ObjectDefinition[][]
     */
    private $definitions = [];

    /**
     * properties.php配置信息
     *
     * @var array
     */
    private $properties = [];

    /**
     * 默认创建bean执行的初始化方法
     *
     * @var string
     */
    private $initMethod = 'init';

    /**
     * 获取一个bean
     *
     * @param string $name 名称
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public function get($name)
    {
        // 已经创建
        if (isset($this->singletonEntries[$name])) {
            return $this->singletonEntries[$name];
        }

        // 未定义
        if (! isset($this->definitions[$name])) {
            throw new ContainerException(sprintf('Bean [%s] not exist', $name));
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->definitions[$name];

        return $this->set($name, $objectDefinition);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->definitions[$id]);
    }

    /**
     * 是否存在某个bean
     *
     * @param string $beanName 名称
     *
     * @return bool
     */
    public function hasBean(string $beanName): bool
    {
        return isset($this->definitions[$beanName]);
    }

    /**
     * 定义配置bean
     *
     * @param array $definitions
     */
    public function addDefinitions(array $definitions)
    {
        $resource          = new DefinitionResource($definitions);
        $this->definitions = array_merge($resource->getDefinitions(), $this->definitions);
    }

    /**
     * Register the annotation of server
     */
    public function autoloadServerAnnotation()
    {
        $bootScan = $this->getScanNamespaceFromProperties('bootScan');
        $resource = new ServerAnnotationResource($this->properties);
        $resource->addScanNamespace($bootScan);
        $definitions = $resource->getDefinitions();

        $this->definitions = array_merge($definitions, $this->definitions);
    }

    /**
     * Register the annotation of worker
     */
    public function autoloadWorkerAnnotation()
    {
        $beanScan = $this->getBeanScanNamespace();
        $resource = new WorkerAnnotationResource($this->properties);
        $resource->addScanNamespace($beanScan);
        $definitions = $resource->getDefinitions();

        $this->definitions = \array_merge($definitions, $this->definitions);
    }

    /**
     * 初始化已定义的bean
     *
     * @throws \Swoft\Exception\ContainerException
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function initBeans()
    {
        $autoInitBeans = $this->properties['autoInitBean'] ?? false;
        if (! $autoInitBeans) {
            return;
        }

        // 循环初始化
        foreach ($this->definitions as $beanName => $definition) {
            $this->get($beanName);
        }
    }

    /**
     * Get all bean definitions
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function getBeanNames(): array
    {
        return \array_keys($this->definitions);
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param string           $name
     * @param ObjectDefinition $objectDefinition Bean definition
     * @return object The proxy class of bean
     * @throws \RuntimeException
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    private function set(string $name, ObjectDefinition $objectDefinition)
    {
        if ($refBeanName = $objectDefinition->getRef()) {
            return $this->get($refBeanName);
        }

        // Get bean definition
        $scope = $objectDefinition->getScope();
        $className = $objectDefinition->getClassName();
        $propertyInjects = $objectDefinition->getPropertyInjections();
        $constructorInject = $objectDefinition->getConstructorInjection();

        // Construtor
        $constructorParameters = [];
        if ($constructorInject !== null) {
            $constructorParameters = $this->getConstructorInjection($constructorInject);
        }

        $proxyClass = $className;
        if ($name !== Aop::class && $this->hasBean(Aop::class)) {
            $proxyClass = $this->getProxyClass($name, $className);
        }

        $reflectionClass = new \ReflectionClass($proxyClass);

        // New bean instance
        $object = $this->newBeanInstance($reflectionClass, $constructorParameters);

        // Inject properties
        $this->injectProperties($object, $reflectionClass->getProperties(), $propertyInjects);

        // Execute 'init' method if exist.
        if ($reflectionClass->hasMethod($this->initMethod)) {
            $object->{$this->initMethod}();
        }

        // Handle bean scope
        if ($scope === Scope::SINGLETON) {
            $this->singletonEntries[$name] = $object;
        }

        return $object;
    }

    /**
     * Get Constructor injection
     *
     * @param MethodInjection $constructorInjection
     * @return array
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    private function getConstructorInjection(MethodInjection $constructorInjection): array
    {
        $constructorParameters = [];

        /* @var ArgsInjection $parameter */
        foreach ($constructorInjection->getParameters() as $parameter) {
            $argValue = $parameter->getValue();
            if (\is_array($argValue)) {
                $constructorParameters[] = $this->injectArrayArgs($argValue);
                continue;
            }
            if ($parameter->isRef()) {
                $constructorParameters[] = $this->get($parameter->getValue());
                continue;
            }
            $constructorParameters[] = $parameter->getValue();
        }

        return $constructorParameters;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param array            $constructorParameters
     * @return object
     */
    private function newBeanInstance(\ReflectionClass $reflectionClass, array $constructorParameters)
    {
        if ($reflectionClass->hasMethod('__construct')) {
            return $reflectionClass->newInstanceArgs($constructorParameters);
        }

        return $reflectionClass->newInstance();
    }

    /**
     * @param  mixed                $object
     * @param \ReflectionProperty[] $properties $properties
     * @param  mixed                $propertyInjects
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    private function injectProperties($object, array $properties, $propertyInjects)
    {
        foreach ($properties as $property) {
            // Cannot handle static property
            if ($property->isStatic()) {
                continue;
            }

            // Is property has injections ?
            $propertyName = $property->getName();
            if (! isset($propertyInjects[$propertyName])) {
                continue;
            }

            // Set property visibility
            if (! $property->isPublic()) {
                $property->setAccessible(true);
            }

            // Get property injection
            /* @var PropertyInjection $propertyInjection */
            $propertyInjection = $propertyInjects[$propertyName];
            $injectProperty = $propertyInjection->getValue();
            if (\is_array($injectProperty)) {
                $injectProperty = $this->injectArrayArgs($injectProperty);
            }

            // Is reference bean ?
            if ($propertyInjection->isRef()) {
                $injectProperty = $this->get($injectProperty);
            }

            if ($injectProperty !== null) {
                $property->setValue($object, $injectProperty);
            }
        }
    }

    /**
     * @param array $injectProperty
     *
     * @return array
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    private function injectArrayArgs(array $injectProperty): array
    {
        $injectAry = [];
        foreach ($injectProperty as $key => $property) {
            // 递归循环注入
            if (\is_array($property)) {
                $injectAry[$key] = $this->injectArrayArgs($property);
                continue;
            }

            // 参数注入
            if ($property instanceof ArgsInjection) {
                $propertyValue = $property->getValue();
                if ($property->isRef()) {
                    $injectAry[$key] = $this->get($propertyValue);
                    continue;
                }
                $injectAry[$key] = $propertyValue;
            }
        }

        if (empty($injectAry)) {
            $injectAry = $injectProperty;
        }

        return $injectAry;
    }

    /**
     * @param string $name
     * @return array
     */
    private function getScanNamespaceFromProperties(string $name): array
    {
        $properties = $this->properties;

        if (! isset($properties[$name]) || ! \is_array($properties[$name])) {
            return [];
        }

        return $properties[$name];
    }

    /**
     * Get the proxy class
     *
     * @param string $name
     * @param string $className
     * @return string
     * @throws \Swoft\Exception\ContainerException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    private function getProxyClass(string $name, string $className): string
    {
        /* @var Aop $aop */
        $aop = $this->get(Aop::class);
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod) {
            $method = $reflectionMethod->getName();
            $annotations = Collector::$methodAnnotations[$className][$method] ?? [];
            $annotations = array_unique($annotations);
            $aop->match($name, $className, $method, $annotations);
        }

        // Init Parser
        ! Proxy::hasParser() && Proxy::initDefaultParser(App::isWorkerStatus());
        return Proxy::newProxyClass($className);
    }

    /**
     * @return array
     */
    private function getBeanScanNamespace(): array
    {
        $beanScan    = $this->getScanNamespaceFromProperties('beanScan');
        $excludeScan = $this->getScanNamespaceFromProperties('excludeScan');
        if (!empty($beanScan)) {
            return array_diff($beanScan, $excludeScan);
        }

        $appDir = alias('@app');
        $dirs   = glob($appDir . '/*');

        $beanNamespace = [];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $nsName          = basename($dir);
            $beanNamespace[] = sprintf('App\%s', $nsName);
        }

        $bootScan = $this->getScanNamespaceFromProperties('bootScan');
        $beanScan = array_diff($beanNamespace, $bootScan, $excludeScan);

        return $beanScan;
    }
}
