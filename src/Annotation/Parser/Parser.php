<?php declare(strict_types=1);

namespace Swoft\Annotation\Annotation\Parser;


/**
 * Class Parser
 *
 * @since 2.0
 */
abstract class Parser implements ParserInterface
{
    /**
     * Class
     */
    const TYPE_CLASS = 1;

    /**
     * Property
     */
    const TYPE_PROPERTY = 2;

    /**
     * Method
     */
    const TYPE_METHOD = 3;

    /**
     * Class name
     *
     * @var string
     */
    protected $className = '';

    /**
     * Class reflect
     *
     * @var \ReflectionClass
     */
    protected $reflectClass;

    /**
     * Class all annotations
     *
     * @var object[]
     */
    protected $classAnnotations = [];

    /**
     * Defintions
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Method name
     *
     * @var string
     */
    protected $methodName = '';

    /**
     * Property name
     *
     * @var string
     */
    protected $propertyName = '';

    /**
     * Parser constructor.
     *
     * @param string           $className
     * @param \ReflectionClass $reflectionClass
     * @param array            $classAnnotations
     */
    public function __construct(string $className, \ReflectionClass $reflectionClass, array $classAnnotations)
    {
        $this->className        = $className;
        $this->reflectClass     = $reflectionClass;
        $this->classAnnotations = $classAnnotations;
    }

    /**
     * Get definition config
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * Set method name
     *
     * @param string $methodName
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     * Set property name
     *
     * @param string $propertyName
     */
    public function setPropertyName(string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }
}