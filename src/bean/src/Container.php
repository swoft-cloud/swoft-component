<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
use function alias;
use function array_diff;
use function array_keys;
use function array_merge;
use function array_unique;
use function basename;
use function glob;
use function is_array;
use function is_dir;
use function sprintf;

/**
 * Bean container, use to manage the all instance(bean) that managed by swoft.
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
     * The bean rules that has been defined.
     *
     * @var ObjectDefinition[][]
     */
    private $definitions = [];

    /**
     * The configuration from config/properties.
     *
     * @var array
     */
    private $properties = [];

    /**
     * Default method that call in bean initialization.
     *
     * @var string
     */
    private $initMethod = 'init';

    /**
     * Get a bean from container.
     *
     * @return mixed
     * @throws ContainerException When the bean does not exist.
     */
    public function get($beanName)
    {
        if (isset($this->singletonEntries[$beanName])) {
            return $this->singletonEntries[$beanName];
        }

        if (! isset($this->definitions[$beanName])) {
            throw new ContainerException(sprintf('Bean [%s] doesn\'t exist.', $beanName));
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->definitions[$beanName];

        return $this->set($beanName, $objectDefinition);
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
        return $this->hasBean($id);
    }

    /**
     * Is the bean exist in container ?
     */
    public function hasBean(string $beanName): bool
    {
        return isset($this->definitions[$beanName]);
    }

    /**
     * Add a bean definitions
     */
    public function addDefinitions(array $definitions)
    {
        $resource = new DefinitionResource($definitions);
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

        $this->definitions = array_merge($definitions, $this->definitions);
    }

    /**
     * Initialize the beans that has been defined.
     */
    public function initBeans()
    {
        $autoInitBeans = $this->properties['autoInitBean'] ?? false;
        if (! $autoInitBeans) {
            return;
        }

        foreach ($this->definitions as $beanName => $definition) {
            $this->get($beanName);
        }
    }

    /**
     * Get all bean definitions
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function getBeanNames(): array
    {
        return array_keys($this->definitions);
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return object The proxy class of bean
     */
    private function set(string $name, ObjectDefinition $beanDefinition)
    {
        if ($refBeanName = $beanDefinition->getRef()) {
            return $this->get($refBeanName);
        }

        // Get bean definition
        $scope = $beanDefinition->getScope();
        $className = $beanDefinition->getClassName();
        $propertyInjects = $beanDefinition->getPropertyInjections();
        $constructorInject = $beanDefinition->getConstructorInjection();

        // Construtor
        $constructorParameters = [];
        if ($constructorInject !== null) {
            $constructorParameters = $this->getConstructorInjection($constructorInject);
        }

        $proxyClass = $className;
        if ($name !== Aop::class && $this->hasBean(Aop::class)) {
            $proxyClass = $this->getProxyClassName($name, $className);
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
     */
    private function getConstructorInjection(MethodInjection $constructorInjection): array
    {
        $constructorParameters = [];

        /* @var ArgsInjection $parameter */
        foreach ($constructorInjection->getParameters() as $parameter) {
            $argValue = $parameter->getValue();
            if (is_array($argValue)) {
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
     * @param  mixed $object
     * @param \ReflectionProperty[] $properties $properties
     * @param  mixed $propertyInjects
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
            if (is_array($injectProperty)) {
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

    private function injectArrayArgs(array $injectProperty): array
    {
        $injectAry = [];
        foreach ($injectProperty as $key => $property) {
            if (is_array($property)) {
                $injectAry[$key] = $this->injectArrayArgs($property);
                continue;
            }

            // Argument injection
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

    private function getScanNamespaceFromProperties(string $name): array
    {
        $properties = $this->properties;

        if (! isset($properties[$name]) || ! is_array($properties[$name])) {
            return [];
        }

        return $properties[$name];
    }

    /**
     * Get the proxy class name
     */
    private function getProxyClassName(string $beanName, string $className): string
    {
        /* @var Aop $aop */
        $aop = $this->get(Aop::class);
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod) {
            $method = $reflectionMethod->getName();
            $annotations = Collector::$methodAnnotations[$className][$method] ?? [];
            $annotations = array_unique($annotations);
            $aop->match($beanName, $className, $method, $annotations);
        }

        // Init Parser
        ! Proxy::hasParser() && Proxy::initDefaultParser(App::isWorkerStatus());
        return Proxy::newProxyClass($className);
    }

    private function getBeanScanNamespace(): array
    {
        $beanScan = $this->getScanNamespaceFromProperties('beanScan');
        $excludeScan = $this->getScanNamespaceFromProperties('excludeScan');
        if (! empty($beanScan)) {
            return array_diff($beanScan, $excludeScan);
        }

        $appDir = alias('@app');
        $dirs = glob($appDir . '/*');

        $beanNamespace = [];
        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                continue;
            }
            $nsName = basename($dir);
            $beanNamespace[] = sprintf('App\%s', $nsName);
        }

        $bootScan = $this->getScanNamespaceFromProperties('bootScan');
        $beanScan = array_diff($beanNamespace, $bootScan, $excludeScan);

        return $beanScan;
    }
}
