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
    const INIT_MEHTOD = 'init';

    /**
     * Destory method before destory bean
     */
    const DESTORY_MEHTOD = 'destroy';

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
     * Bean static proxy
     *
     * @var ClassProxyInterface
     */
    private $classProxy;

    /**
     * Bean dynamic proxy
     *
     * @var ObjectProxyInterface
     */
    private $objectProxy;

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
     * Singleton pool
     *
     * @var array
     */
    private $singletonPool = [];

    /**
     * @var ReferenceInterface
     */
    private $reference;

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
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Init
     *
     * @return void
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

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Bean name Or alias Or class name
     *
     * When class name will return all of instance for class name
     *
     * @return object
     */
    public function get($id)
    {
        // Singleton
        if (isset($this->singletonPool[$id])) {
            return $this->singletonPool[$id];
        }

        // Alias
        $aliasId = $this->aliases[$id] ?? '';
        if (!empty($aliasId) && isset($this->singletonPool[$aliasId])) {
            return $this->get($aliasId);
        }

        // Class name
        $classNameBeans = [];
        $classNameIds   = $this->classNames[$id] ?? [];
        foreach ($classNameIds as $cid) {
            $classNameBeans[] = $this->get($cid);
        }

        if (!empty($classNameBeans)) {
            return $classNameBeans;
        }

        // Not defined
        if (!isset($this->objectDefinitions[$id])) {
            throw new ContainerException('The bean of' . $id . 'is not defined');
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->objectDefinitions[$id];

        // Prototype
        return $this->newBean($objectDefinition->getName());
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
     * @param string $alias
     *
     * @return object
     */
    public function create(string $name, array $definition = [])
    {
        if ($this->has($name)) {
            throw new ContainerException('Create ' . $name . ' bean by definition is exist!');
        }

        //  Create bean only by class name
        if (class_exists($name) && empty($definition)) {
            $definition = [
                'class' => $name
            ];
        }

        $definitions = [
            $name => $definition
        ];

        $definitionObjParser = new DefinitionObjParser($definition, []);
        list(, $objectDefinitions) = $definitionObjParser->parseDefinitions();

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

        if (isset($this->aliases)) {
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
        if (!isset($this->singletonPool[$name])) {
            return false;
        }

        $alias = $this->aliases[$name] ?? '';
        if (!empty($alias) && !$this->isSingleton($name)) {
            return false;
        }

        return true;
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
     * Set class proxy
     *
     * @param ClassProxyInterface $classProxy
     *
     * @return void
     */
    public function setClassProxy(ClassProxyInterface $classProxy): void
    {
        $this->classProxy = $classProxy;
    }

    /**
     * Set object proxy
     *
     * @param ObjectProxyInterface $objectProxy
     *
     * @return void
     */
    public function setObjectProxy(ObjectProxyInterface $objectProxy): void
    {
        $this->objectProxy = $objectProxy;
    }

    /**
     * @param ReferenceInterface $reference
     */
    public function setReference(ReferenceInterface $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * Parse annotations
     */
    private function parseAnnotations(): void
    {
        $annotationParser = new AnnotationObjParser($this->definitions, $this->objectDefinitions);
        $annotationData   = $annotationParser->parseAnnotations($this->annotations, $this->parsers);

        list($this->definitions, $this->objectDefinitions) = $annotationData;
    }

    /**
     * Parse definitions
     */
    private function parseDefinitions(): void
    {
        $annotationParser = new DefinitionObjParser($this->definitions, $this->objectDefinitions);
        $annotationData   = $annotationParser->parseDefinitions();

        list($this->definitions, $this->objectDefinitions) = $annotationData;
    }

    /**
     * Initialize beans
     */
    private function initializeBeans()
    {
        /* @var ObjectDefinition $objectDefinition */
        foreach ($this->objectDefinitions as $beanName => $objectDefinition) {
            $this->newBean($beanName);
        }
    }

    /**
     * Initialize beans
     *
     * @param string $beanName
     *
     * @return object
     */
    private function newBean(string $beanName)
    {
        if (isset($this->singletonPool[$beanName])) {
            return $this->singletonPool[$beanName];
        }

        if (!isset($this->objectDefinitions[$beanName])) {
            throw new ContainerException('Bean name of ' . $beanName . ' is not defined!');
        }

        $objectDefinition = $this->objectDefinitions[$beanName];

        $name      = $objectDefinition->getName();
        $scope     = $objectDefinition->getScope();
        $alias     = $objectDefinition->getAlias();
        $className = $objectDefinition->getClassName();

        $constructArgs   = [];
        $constructInject = $objectDefinition->getConstructorInjection();
        if (!empty($constructInject)) {
            $constructArgs = $this->getConstructParams($constructInject);
        }

        $propertyInjects = $objectDefinition->getPropertyInjections();

        // Proxy class
        if (!empty($this->classProxy)) {
            $className = $this->classProxy->proxy($className);
        }

        $reflectionClass = new \ReflectionClass($className);
        $reflectObject   = $this->newInstance($reflectionClass, $constructArgs);

        $this->newProperty($reflectObject, $reflectionClass, $propertyInjects);

        // Init method
        if ($reflectionClass->hasMethod(self::INIT_MEHTOD)) {
            $reflectObject->{self::INIT_MEHTOD}();
        }

        if ($scope == Bean::PROTOTYPE) {
            return $reflectObject;
        }

        if (!empty($alias)) {
            $this->aliases[$alias] = $beanName;
        }

        $this->classNames[$className]   = $beanName;
        $this->singletonPool[$beanName] = $reflectObject;

        return $reflectObject;
    }

    /**
     * Get construct args
     *
     * @param MethodInjection $methodInjection
     *
     * @return array
     * @throws ContainerException
     */
    private function getConstructParams(MethodInjection $methodInjection): array
    {
        $methodName = $methodInjection->getMethodName();
        if ($methodName != '__construct') {
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
            if (empty($argValue) || !is_string($argValue)) {
                $args[] = $argValue;
                continue;
            }

            $isRef = $argInject->isRef();
            if ($isRef) {
                $argValue = $this->getRefValue($argValue);;
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
     * @param object $reflectObject
     * @param array  $propertyInjects
     *
     * @return object
     */
    private function newProperty($reflectObject, \ReflectionClass $reflectionClass, array $propertyInjects)
    {
        // New parent properties
        $parentClass = $reflectionClass->getParentClass();
        if ($parentClass !== false) {
            $this->newProperty($reflectObject, $parentClass, $propertyInjects);
        }

        /* @var PropertyInjection $propertyInject */
        foreach ($propertyInjects as $propertyInject) {
            $propertyName = $propertyInject->getPropertyName();
            if (!$reflectionClass->hasProperty($propertyName)) {
                continue;
            }

            $reflectProperty = $reflectionClass->getProperty($propertyName);

            if ($reflectProperty->isStatic()) {
                throw new ContainerException('Property %s for bean can not be `static` ', $propertyName);
            }

            if (!$reflectProperty->isPublic()) {
                $reflectProperty->setAccessible(true);
            }

            $propertyValue = $propertyInject->getValue();
            if (is_array($propertyValue)) {
                $propertyValue = $this->newPropertyArray($propertyValue);
            }

            $isRef = $propertyInject->isRef();
            if ($isRef) {
                $propertyValue = $this->getRefValue($propertyValue);
            }

            $reflectProperty->setValue($reflectObject, $propertyValue);
        }
    }

    /**
     * New property array
     *
     * @param array $propertyValue
     *
     * @return array
     */
    private function newPropertyArray(array $propertyValue): array
    {
        foreach ($propertyValue as $proKey => &$proValue) {
            if ($proValue instanceof ArgsInjection && $proValue->isRef()) {
                $refValue = $proValue->getValue();
                $proValue = $this->getRefValue($refValue);
            }
        }

        return $propertyValue;
    }

    /**
     * Get ref value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function getRefValue($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $refs = explode('.', $value);
        if (count($refs) == 1) {
            return $this->newBean($value);
        }

        // Other reference
        if (!empty($this->reference)) {
            $value = $this->reference->getValue($value);
        }

        return $value;
    }
}