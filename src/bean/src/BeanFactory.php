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
use Swoft\Bean\Collector\BootBeanCollector;
use Swoft\Bean\Collector\DefinitionCollector;
use Swoft\Core\Config;
use Swoft\Helper\ArrayHelper;
use Swoft\Helper\DirHelper;

class BeanFactory implements BeanFactoryInterface
{
    /**
     * @var ContainerInterface Bean container
     */
    private static $container;

    /**
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public static function init()
    {
        $properties = self::getProperties();
        static::$container = new Container();
        static::$container->setProperties($properties);
        static::$container->autoloadServerAnnotation();

        $definition = static::getServerDefinition();
        static::$container->addDefinitions($definition);
        static::$container->initBeans();
    }

    /**
     * Reload bean definitions
     *
     * @param array $definitions append definitions to config loader
     * @throws \ReflectionException
     */
    public static function reload(array $definitions = [])
    {
        $properties = self::getProperties();
        $workerDefinitions = self::getWorkerDefinition();
        $definitions = ArrayHelper::merge($workerDefinitions, $definitions);

        static::$container->setProperties($properties);
        static::$container->addDefinitions($definitions);
        static::$container->autoloadWorkerAnnotation();

        $componentDefinitions = static::getComponentDefinitions();
        static::$container->addDefinitions($componentDefinitions);

        /* @var Aop $aop Init reload AOP */
        $aop = static::getBean(Aop::class);
        $aop->init();

        static::$container->initBeans();
    }

    /**
     * Get bean from container
     */
    public static function getBean(string $name)
    {
        return static::$container->get($name);
    }

    /**
     * Determine if bean exist in container
     */
    public static function hasBean(string $name): bool
    {
        return static::$container->hasBean($name);
    }

    private static function getWorkerDefinition(): array
    {
        $configDefinitions = [];
        $beansDir = alias('@beans');

        if (\is_readable($beansDir)) {
            $config = new Config();
            $config->load($beansDir, [], DirHelper::SCAN_BFS, Config::STRUCTURE_MERGE);
            $configDefinitions = $config->toArray();
        }

        $coreBeans = static::getCoreBeans(BootBeanCollector::TYPE_WORKER);

        return ArrayHelper::merge($coreBeans, $configDefinitions);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function getServerDefinition(): array
    {
        $file = alias('@console');
        $configDefinition = [];

        if (\is_readable($file)) {
            $configDefinition = require_once $file;
        }

        $coreBeans = static::getCoreBeans(BootBeanCollector::TYPE_SERVER);

        return ArrayHelper::merge($coreBeans, $configDefinition);
    }

    private static function getProperties(): array
    {
        $properties = [];
        $config = new Config();
        $dir = alias('@properties');

        if (\is_readable($dir)) {
            $config->load($dir);
            $properties = $config->toArray();
        }

        return $properties;
    }

    private static function getCoreBeans(string $type): array
    {
        $collector = BootBeanCollector::getCollector();
        if (! isset($collector[$type])) {
            return [];
        }

        $coreBeans = [];
        /** @var array $bootBeans */
        $bootBeans = $collector[$type];
        foreach ($bootBeans as $beanName) {
            /* @var \Swoft\Core\BootBeanInterface $bootBean */
            $bootBean = self::getBean($beanName);
            $beans = $bootBean->beans();
            $coreBeans = ArrayHelper::merge($coreBeans, $beans);
        }

        return $coreBeans;
    }

    private static function getComponentDefinitions(): array
    {
        $definitions = [];
        $collector = DefinitionCollector::getCollector();

        foreach ($collector as $className => $beanName) {
            /* @var \Swoft\Bean\DefinitionInterface $definition */
            $definition = static::getBean($beanName);

            $definitions = ArrayHelper::merge($definitions, $definition->getDefinitions());
        }

        return $definitions;
    }

    public static function getContainer(): ContainerInterface
    {
        return static::$container;
    }
}
