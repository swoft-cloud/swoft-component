<?php

namespace Swoft\Bean;

use function foo\func;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class BeanFactory
 */
class BeanFactory
{
    /**
     * Init
     *
     * @return void
     */
    public static function init(): void
    {
        Container::getInstance()->init();
    }

    /**
     * Get object by name
     *
     * @param string $name
     *
     * @return object
     */
    public static function getBean(string $name)
    {
        return Container::getInstance()->get($name);
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
     */
    public static function createBean(string $name, array $definition = [])
    {
        return Container::getInstance()->create($name, $definition);
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
     * Set class proxy
     *
     * @param ClassProxyInterface $classProxy
     *
     * @return void
     */
    public static function setClassProxy(ClassProxyInterface $classProxy): void
    {
        Container::getInstance()->setClassProxy($classProxy);
    }

    /**
     * Set object proxy
     *
     * @param ObjectProxyInterface $objectProxy
     *
     * @return void
     */
    public static function setObjectProxy(ObjectProxyInterface $objectProxy): void
    {
        Container::getInstance()->setObjectProxy($objectProxy);
    }

    /**
     * Set reference
     *
     * @param ReferenceInterface $reference
     *
     * @return void
     */
    public static function setReference(ReferenceInterface $reference): void
    {
        Container::getInstance()->setReference($reference);
    }
}