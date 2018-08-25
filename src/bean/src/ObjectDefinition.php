<?php

namespace Swoft\Bean;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\ObjectDefinition\MethodInjection;

/**
 * Bean object definition
 */
class ObjectDefinition
{
    /**
     * Entry name (most of the time, same as $classname).
     *
     * @var string
     */
    private $name;

    /**
     * Class name (if null, then the class name is $name).
     *
     * @var string|null
     */
    private $className;

    /**
     * @var int
     */
    private $scope = Scope::SINGLETON;

    /**
     * Referenced bean, default is null
     *
     * @var string|null
     */
    private $ref;

    /**
     * Constructor parameter injection.
     *
     * @var MethodInjection|null
     */
    private $constructorInjection = null;

    /**
     * Property injections.
     * @var array
     */
    private $propertyInjections = [];

    /**
     * Method calls.
     *
     * @var MethodInjection[][]
     */
    private $methodInjections = [];

    /**
     * Get bean name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set bean name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get classname of Bean
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set classname of Bean
     *
     * @param string $className
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
    }

    /**
     * Get scopt of Bean
     *
     * @return int
     */
    public function getScope(): int
    {
        return $this->scope;
    }


    /**
     * get referenced bean
     *
     * @return string|null
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * set referenced bean
     *
     * @param string $ref
     */
    public function setRef(string $ref)
    {
        $this->ref = $ref;
    }

    /**
     * Set scope of Bean
     *
     * @param int $scope
     */
    public function setScope(int $scope)
    {
        $this->scope = $scope;
    }

    /**
     * Get constructor injection object
     *
     * @return MethodInjection|null
     */
    public function getConstructorInjection()
    {
        return $this->constructorInjection;
    }

    /**
     * Set constructor injection object
     *
     * @param MethodInjection $constructorInjection
     */
    public function setConstructorInjection(MethodInjection $constructorInjection)
    {
        $this->constructorInjection = $constructorInjection;
    }

    /**
     * Get property injection object
     *
     * @return mixed
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }

    /**
     * Set property injection object
     *
     * @param mixed $propertyInjections
     */
    public function setPropertyInjections($propertyInjections)
    {
        $this->propertyInjections = $propertyInjections;
    }

    /**
     * Get method injection object
     *
     * @return ObjectDefinition\MethodInjection[][]
     */
    public function getMethodInjections(): array
    {
        return $this->methodInjections;
    }

    /**
     * Set method injection object
     *
     * @param ObjectDefinition\MethodInjection[][] $methodInjections
     */
    public function setMethodInjections(array $methodInjections)
    {
        $this->methodInjections = $methodInjections;
    }
}
