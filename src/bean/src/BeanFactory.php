<?php

namespace Swoft\Bean;

use Swoft\Bean\Exception\ContainerException;

/**
 * Class BeanFactory
 */
class BeanFactory
{
    /**
     * Init
     *
     * @return void
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function init(): void
    {
        Container::getInstance()->init();
    }

    /**
     * Get object by name
     *
     * @param string $name Bean name Or alias Or class name
     *
     * @return object|mixed
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function getBean(string $name)
    {
        return Container::getInstance()->get($name);
    }

    /**
     * Get request bean
     *
     * @param string $name
     * @param int    $id
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function getRequestBean(string $name, int $id)
    {
        return Container::getInstance()->getRequest($name, $id);
    }

    /**
     * Get session bean
     *
     * @param string $name
     * @param int    $id
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function getSessionBean(string $name, int $id)
    {
        return Container::getInstance()->getSession($name, $id);
    }

    /**
     * Create bean by definition
     *
     * @param string $name
     * @param array  $definition
     *
     * @example
     *
     * $bean = BeanFactory::createBean('className');
     *
     * $bean = BeanFactory::createBean('name', [
     *     'class' => 'className',
     *     ......
     * ]);
     *
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function createBean(string $name, array $definition = [])
    {
        return Container::getInstance()->create($name, $definition);
    }

    /**
     * Destroy request bean
     *
     * @param int $id
     */
    public static function destroyRequest(int $id): void
    {
        Container::getInstance()->destroyRequest($id);
    }

    /**
     * Destroy session bean
     *
     * @param int $id
     */
    public static function destroySession(int $id): void
    {
        Container::getInstance()->destroySession($id);
    }

    /**
     * Whether has bean
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasBean(string $name): bool
    {
        return Container::getInstance()->has($name);
    }

    /**
     * Whether is singleton
     *
     * @param string $name
     *
     * @return bool
     */
    public static function isSingleton(string $name): bool
    {
        return Container::getInstance()->isSingleton($name);
    }


    /**
     * Add definitions
     *
     * @param array $definitions
     *
     * @return void
     */
    public static function addDefinitions(array $definitions): void
    {
        Container::getInstance()->addDefinitions($definitions);
    }

    /**
     * Add annotations
     *
     * @param array $annotations
     *
     * @return void
     */
    public static function addAnnotations(array $annotations): void
    {
        Container::getInstance()->addAnnotations($annotations);
    }

    /**
     * Add annotation parsers
     *
     * @param array $annotationParsers
     *
     * @return void
     */
    public static function addParsers(array $annotationParsers): void
    {
        Container::getInstance()->addParsers($annotationParsers);
    }

    /**
     * Set bean handler
     *
     * @param HandlerInterface $handler
     */
    public static function setHandler(HandlerInterface $handler): void
    {
        Container::getInstance()->setHandler($handler);
    }

    /**
     * @param string $className
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getReflection(string $className): array
    {
        return Container::getInstance()->getReflection($className);
    }
}