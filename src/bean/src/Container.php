<?php

namespace Swoft\Bean;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Definition\ArgsInjection;
use Swoft\Bean\Definition\MethodInjection;
use Swoft\Bean\Definition\ObjectDefinition;
use Swoft\Bean\Definition\Parser\AnnotationObjParser;
use Swoft\Bean\Definition\Parser\DefinitionObjParser;
use Swoft\Bean\Definition\PropertyInjection;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    /**
     * Init method after create bean
     */
    public const INIT_METHOD = 'init';

    /**
     * Destroy method before destroy bean
     */
    public const DESTROY_METHOD = 'destroy';

    /**
     * Default pool size
     */
    public const DEFAULT_POOL_SIZE = 100;

    /**
     * @var Container
     */
    private static $instance;

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
     * All definitions
     *
     * @var array
     *
     * @example
     * [
     *     'name' => [
     *         'class' => 'className',
     *         [
     *             'construnctArg',
     *             '${ref.name}', // config params
     *             '${beanName}', // object
     *         ],
     *         'propertyValue',
     *         '${ref.name}',
     *         '${beanName}',
     *         '__option' => [
     *              'scope' => '...',
     *              'alias' => '...',
     *         ]
     *     ]
     * ]
     */
    private $definitions = [];

    /**
     * All alias
     *
     * @var array
     *
     * @example
     * [
     *     'alias' => 'beanName',
     *     'alias' => 'beanName',
     *     'alias' => 'beanName'
     * ]
     */
    private $aliases = [];

    /**
     * Class all bean names (many instances)
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'beanName',
     *         'beanName',
     *         'beanName',
     *     ]
     * ]
     */
    private $classNames = [];

    /**
     * Bean definitions
     *
     * @var ObjectDefinition[]
     *
     * @example
     * [
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition
     * ]
     */
    private $objectDefinitions = [];

    /**
     * Request bean definitions
     *
     * @var ObjectDefinition[]
     *
     * @example
     * [
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition
     * ]
     */
    private $requestDefinitions = [];

    /**
     * Session bean definitions
     *
     * @var ObjectDefinition[]
     *
     * @example
     * [
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition
     * ]
     */
    private $sessionDefinitions = [];

    /**
     * Singleton pool
     *
     * @var array
     *
     * @example
     * [
     *     'beanName' => object,
     *     'beanName' => object,
     *     'beanName' => object,
     * ]
     */
    private $singletonPool = [];

    /**
     * Prototype pool
     *
     * @var array
     *
     * @example
     * [
     *     'beanName' => object,
     *     'beanName' => object,
     *     'beanName' => object,
     * ]
     */
    private $prototypePool = [];

    /**
     * Request pool
     *
     * @var array
     *
     * @example
     * [
     *     'beanName' => object,
     *     'beanName' => object,
     *     'beanName' => object,
     * ]
     */
    private $requestPool = [];

    /**
     * Session pool
     *
     * @var array
     *
     * @example
     * [
     *     'beanName' => object,
     *     'beanName' => object,
     *     'beanName' => object,
     * ]
     */
    private $sessionPool = [];

    /**
     * Reflection pool
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'comments' => 'class doc comments',
     *         'methods'  => [
     *             'methodName' => [
     *                'params'     => [
     *                    'argType',  // like `int $arg`
     *                    'argType',  // like `class $arg`
     *                    null // like `$arg`
     *                ],
     *                'comments'   => 'method doc comments',
     *                'returnType' => 'returnType/null'
     *            ]
     *         ]
     *     ]
     * ]
     */
    private $reflectionPool = [];

    /**
     * Bean handler
     *
     * @var HandlerInterface
     */
    private $handler;

    /**
     * Container constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return Container
     */
    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Init
     *
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function init(): void
    {
        // Parse annotations
        $this->parseAnnotations();

        // Parse definitions
        $this->parseDefinitions();

        // Init beans
        $this->initializeBeans();
    }

    public function initializeRequest(int $rid)
    {
//        /* @var ObjectDefinition $objectDefinition */
//        foreach ($this->requestDefinitions as $beanName => $objectDefinition) {
//
//        }
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Bean name Or alias Or class name
     *
     * When class name will return all of instance for class name
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function get($id)
    {
        // Singleton
        if (isset($this->singletonPool[$id])) {
            return $this->singletonPool[$id];
        }

        // Prototype by clone
        if (isset($this->prototypePool[$id])) {
            return clone $this->prototypePool[$id];
        }

        // Alias
        $aliasId = $this->aliases[$id] ?? '';
        if (!empty($aliasId)) {
            return $this->get($aliasId);
        }

        // Class name
        $classNames = $this->classNames[$id] ?? [];
        if (!empty($classNames)) {
            $id = end($classNames);
            return $this->get($id);
        }

        // Not defined
        if (!isset($this->objectDefinitions[$id])) {
            throw new ContainerException(sprintf('The bean of %s is not defined', $id));
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->objectDefinitions[$id];

        // Prototype
        return $this->newBean($objectDefinition->getName());
    }

    /**
     * Get reflection array by className
     *
     * @param string $className
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getReflection(string $className): array
    {
        // Not exist
        if (!isset($this->reflectionPool[$className])) {
            $this->cacheReflectionClass($className);
        }

        return $this->reflectionPool[$className];
    }

    /**
     * Create object by definition
     *
     * @param string $name
     * @param array  $definition
     *
     * @example
     * [
     *     'class' =>  'className',
     *     [
     *         'arg',
     *         '${bean}',
     *         '${config.xxx.xxx}'
     *     ], // Index = 0 is constructor
     *
     *     'propertyName' => 'propertyValue',
     *     'propertyName' => '${bean}',
     *     'propertyName' => '${xxx.xxx.xxx}',
     *     '__option' => [
     *         'scope' => Bean::xxx,
     *         'alias' => 'aliasName'
     *     ]
     * ]
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function create(string $name, array $definition = [])
    {
        if ($this->has($name)) {
            throw new ContainerException('Create ' . $name . ' bean by definition is exist!');
        }

        //  Create bean only by class name
        if (empty($definition) && \class_exists($name)) {
            $definition = [
                'class' => $name
            ];
        }

        $definitionObjParser = new DefinitionObjParser($definition, [], []);
        [, $objectDefinitions] = $definitionObjParser->parseDefinitions();

        $this->objectDefinitions[$name] = $objectDefinitions[$name];

        return $this->newBean($name);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Bean name Or alias Or class name
     *
     * @return bool
     */
    public function has($id): bool
    {
        if (isset($this->singletonPool[$id])) {
            return true;
        }

        if (isset($this->aliases[$id])) {
            return true;
        }

        if (isset($this->classNames[$id])) {
            return true;
        }

        if (isset($this->objectDefinitions[$id])) {
            return true;
        }

        return false;
    }

    /**
     * Whether is singleton
     *
     * @param string $name Bean name Or alias
     *
     * @return bool
     */
    public function isSingleton(string $name): bool
    {
        if (isset($this->aliases[$name])) {
            $name = $this->aliases[$name];
        }

        return isset($this->singletonPool[$name]);
    }

    /**
     * Add definitions
     *
     * @param array $definitions
     *
     * @return void
     */
    public function addDefinitions(array $definitions): void
    {
        $this->definitions = ArrayHelper::merge($this->definitions, $definitions);
    }

    /**
     * Add annotations
     *
     * @param array $annotations
     *
     * @return void
     */
    public function addAnnotations(array $annotations): void
    {
        $this->annotations = ArrayHelper::merge($this->annotations, $annotations);
    }

    /**
     * Add annotation parsers
     *
     * @param array $annotationParsers
     *
     * @return void
     */
    public function addParsers(array $annotationParsers): void
    {
        $this->parsers = ArrayHelper::merge($this->parsers, $annotationParsers);
    }

    /**
     * Parse annotations
     *
     * @throws ContainerException
     */
    private function parseAnnotations(): void
    {
        $annotationParser = new AnnotationObjParser($this->definitions, $this->objectDefinitions, $this->classNames);
        $annotationData   = $annotationParser->parseAnnotations($this->annotations, $this->parsers);

        [$this->definitions, $this->objectDefinitions, $this->classNames] = $annotationData;
    }

    /**
     * Parse definitions
     *
     * @throws ContainerException
     */
    private function parseDefinitions(): void
    {
        $annotationParser = new DefinitionObjParser($this->definitions, $this->objectDefinitions, $this->classNames);
        // collect info
        [$this->definitions, $this->objectDefinitions, $this->classNames] = $annotationParser->parseDefinitions();
    }

    /**
     * @param HandlerInterface $handler
     */
    public function setHandler(HandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * Initialize beans
     *
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function initializeBeans(): void
    {
        /* @var ObjectDefinition $objectDefinition */
        foreach ($this->objectDefinitions as $beanName => $objectDefinition) {
            $scope = $objectDefinition->getScope();
            // Exclude request
            if ($scope == Bean::REQUEST) {
                $this->requestDefinitions[$beanName] = $objectDefinition;
                unset($this->objectDefinitions[$beanName]);
                continue;
            }

            // Exclude session
            if ($scope == Bean::SESSION) {
                $this->sessionDefinitions[$beanName] = $objectDefinition;
                unset($this->objectDefinitions[$beanName]);
                continue;
            }

            // New bean
            $this->newBean($beanName);
        }
    }

    /**
     * @param string $beanName
     *
     * @return ObjectDefinition
     * @throws ContainerException
     */
    private function getNewObjectDefinition(string $beanName): ObjectDefinition
    {
         if (isset($this->objectDefinitions[$beanName])) {
            return $this->objectDefinitions[$beanName];
        }

        if (isset($this->requestDefinitions[$beanName])) {
            return $this->requestDefinitions[$beanName];
        }

        if (isset($this->sessionDefinitions[$beanName])) {
            return $this->sessionDefinitions[$beanName];
        }

        $classNames = $this->classNames[$beanName] ?? [];
        if (!empty($classNames)) {
            $beanName = \end($classNames);
            return $this->getNewObjectDefinition($beanName);
        }

        throw new ContainerException('Bean name of ' . $beanName . ' is not defined!');
    }

    /**
     * @param string $beanName
     * @param string $scope
     * @param object $object
     * @param int    $id
     *
     * @return object
     */
    private function setNewBean(string $beanName, string $scope, $object, int $id = 0)
    {
        switch ($scope) {
            case Bean::SINGLETON:
                // Singleton
                $this->singletonPool[$beanName] = $object;
                break;
            case Bean::PROTOTYPE:
                $this->prototypePool[$beanName] = $object;

                $object = clone $object;
                break;
            case Bean::REQUEST:
                $this->requestPool[$id][$beanName] = $object;
                break;
            case Bean::SESSION:
                $this->sessionPool[$id][$beanName] = $object;
                break;
        }

        return $object;
    }

    /**
     * Initialize beans
     *
     * @param string $beanName
     * @param int    $id
     *
     * @throws ContainerException
     * @throws \ReflectionException
     * @return object
     */
    private function newBean(string $beanName, int $id = 0)
    {
        // Get object definition
        $objectDefinition = $this->getNewObjectDefinition($beanName);

        $scope     = $objectDefinition->getScope();
        $alias     = $objectDefinition->getAlias();
        $className = $objectDefinition->getClassName();

        // Cache reflection class
        $this->cacheReflectionClass($className);

        // Before initialize bean
        $this->beforeInit($beanName, $className, $objectDefinition);

        $constructArgs   = [];
        $constructInject = $objectDefinition->getConstructorInjection();
        if ($constructInject !== null) {
            $constructArgs = $this->getConstructParams($constructInject, $id);
        }

        $propertyInjects = $objectDefinition->getPropertyInjections();

        // Proxy class
        if ($this->handler) {
            $className = $this->handler->classProxy($className);
        }

        $reflectionClass = new \ReflectionClass($className);
        $reflectObject   = $this->newInstance($reflectionClass, $constructArgs);

        $this->newProperty($reflectObject, $reflectionClass, $propertyInjects, $id);

        // Alias
        if (!empty($alias)) {
            $this->aliases[$alias] = $beanName;
        }

        // Init method
        if ($reflectionClass->hasMethod(self::INIT_METHOD)) {
            $reflectObject->{self::INIT_METHOD}();
        }

        return $this->setNewBean($beanName, $scope, $reflectObject, $id);
    }

    /**
     * Get construct args
     *
     * @param MethodInjection $methodInjection
     * @param int             $id
     *
     * @return array
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function getConstructParams(MethodInjection $methodInjection, int $id = 0): array
    {
        $methodName = $methodInjection->getMethodName();
        if ($methodName !== '__construct') {
            throw new ContainerException('ConstructInjection method must be `__construct`');
        }

        $argInjects = $methodInjection->getParameters();
        if (empty($argInjects)) {
            return [];
        }

        $args = [];
        /* @var ArgsInjection $arg */
        foreach ($argInjects as $argInject) {

            // Empty args
            $argValue = $argInject->getValue();
            if (empty($argValue) || !\is_string($argValue)) {
                $args[] = $argValue;
                continue;
            }

            $isRef = $argInject->isRef();
            if ($isRef) {
                $argValue = $this->getRefValue($argValue, $id);
            }

            $args[] = $argValue;
        }

        return $args;
    }

    /**
     * New bean instance
     *
     * @param \ReflectionClass $reflectionClass
     * @param array            $args
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function newInstance(\ReflectionClass $reflectionClass, array $args)
    {
        if (empty($args) || !$reflectionClass->hasMethod('__construct')) {
            return $reflectionClass->newInstance();
        }

        $reflectMethod = $reflectionClass->getMethod('__construct');
        if ($reflectMethod->isPrivate() || $reflectMethod->isProtected()) {
            throw new ContainerException('Construct function for bean must be public!');
        }

        return $reflectionClass->newInstanceArgs($args);
    }

    /**
     * New bean property
     *
     * @param  object          $reflectObject
     * @param \ReflectionClass $reflectionClass
     * @param array            $propertyInjects
     * @param int              $id
     *
     * @return void
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function newProperty(
        $reflectObject,
        \ReflectionClass $reflectionClass,
        array $propertyInjects,
        int $id = 0
    ): void {
        // New parent properties
        $parentClass = $reflectionClass->getParentClass();
        if ($parentClass !== false) {
            $this->newProperty($reflectObject, $parentClass, $propertyInjects, $id);
        }

        /* @var PropertyInjection $propertyInject */
        foreach ($propertyInjects as $propertyInject) {
            $propertyName = $propertyInject->getPropertyName();
            if (!$reflectionClass->hasProperty($propertyName)) {
                continue;
            }

            $reflectProperty = $reflectionClass->getProperty($propertyName);

            if ($reflectProperty->isStatic()) {
                throw new ContainerException(\sprintf('Property %s for bean can not be `static` ', $propertyName));
            }

            if (!$reflectProperty->isPublic()) {
                $reflectProperty->setAccessible(true);
            }

            $propertyValue = $propertyInject->getValue();
            if (is_array($propertyValue)) {
                $propertyValue = $this->newPropertyArray($propertyValue, $id);
            }

            $isRef = $propertyInject->isRef();
            if ($isRef) {
                $propertyValue = $this->getRefValue($propertyValue, $id);
            }

            $reflectProperty->setValue($reflectObject, $propertyValue);
        }
    }

    /**
     * @param string $className
     *
     * @throws \ReflectionException
     */
    private function cacheReflectionClass(string $className): void
    {
        if (isset($this->reflectionPool[$className])) {
            return;
        }

        $reflectionClass   = new \ReflectionClass($className);
        $reflectionMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $this->reflectionPool[$className]['name']     = $reflectionClass->getName();
        $this->reflectionPool[$className]['comments'] = $reflectionClass->getDocComment();

        foreach ($reflectionMethods as $reflectionMethod) {
            $methodName   = $reflectionMethod->getName();
            $methodParams = [];

            foreach ($reflectionMethod->getParameters() as $parameter) {
                $defaultValue = null;
                if ($parameter->isDefaultValueAvailable()) {
                    $defaultValue = $parameter->getDefaultValue();
                }

                $methodParams[] = [
                    $parameter->getName(),
                    $parameter->getType(),
                    $defaultValue
                ];
            }

            $this->reflectionPool[$className]['methods'][$methodName]['params']     = $methodParams;
            $this->reflectionPool[$className]['methods'][$methodName]['comments']   = $reflectionMethod->getDocComment();
            $this->reflectionPool[$className]['methods'][$methodName]['returnType'] = $reflectionMethod->getReturnType();
        }
    }

    /**
     * Before initialize bean
     *
     * @param string           $beanName
     * @param string           $className
     * @param ObjectDefinition $objectDefinition
     */
    private function beforeInit(string $beanName, string $className, ObjectDefinition $objectDefinition): void
    {
        if ($this->handler === null) {
            return;
        }

        $annotation = [];
        foreach ($this->annotations as $ns => $classAnnotations) {
            $annotation = $classAnnotations[$className] ?? $annotation;
        }

        $this->handler->beforeInit($beanName, $className, $objectDefinition, $annotation);
    }

    /**
     * New property array
     *
     * @param array $propertyValue
     * @param int   $id
     *
     * @return array
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function newPropertyArray(array $propertyValue, int $id = 0): array
    {
        foreach ($propertyValue as $proKey => &$proValue) {
            if ($proValue instanceof ArgsInjection && $proValue->isRef()) {
                $refValue = $proValue->getValue();
                $proValue = $this->getRefValue($refValue, $id);
            }
        }

        return $propertyValue;
    }

    /**
     * Get ref value
     *
     * @param mixed $value
     * @param int   $id
     *
     * @return mixed
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function getRefValue($value, int $id = 0)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (false === \strpos($value, '.')) {
            return $this->newBean($value, $id);
        }

        // Other reference
        if ($this->handler !== null) {
            $value = $this->handler->getReferenceValue($value);
        }

        return $value;
    }
}