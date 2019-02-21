<?php

namespace Swoft\Console\Bean\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Helper\CommandHelper;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Helper\Str;

/**
 * Class CommandParser
 *
 * @since 2.0
 *
 * @AnnotationParser(Command::class)
 */
class CommandParser extends Parser
{
    /**
     * @var array
     * [
     *  class => [
     *
     *      commands => []
     *  ]
     * ]
     */
    private static $commands = [];

    /**
     * Parse object
     *
     * @param int     $type Class or Method or Property
     * @param Command $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@Command` must be defined on class!');
        }

        $class = $this->className;

        // add route for the command controller
        self::$commands[$class] = [
            'group'     => $annotation->getName() ?: Str::getClassName($class, 'Command'),
            'desc'      => $annotation->getDesc(),
            'alias'     => $annotation->getAlias(),
            'enabled'   => $annotation->isEnabled(),
            'coroutine' => $annotation->isCoroutine(),
        ];

        return [$class, $class, Bean::SINGLETON, ''];
    }

    /**
     * @param string $class
     * @param array  $info
     */
    public static function addRoute(string $class, string $method, array $info): void
    {
        self::$commands[$class]['commands'][$method] = $info;
    }

    /**
     * @param string $class
     * @param array  $info
     */
    public static function bindArgument(string $class, array $info): void
    {
        self::$commands[$class]['routes'][] = $info;
    }

    /**
     * @param string $class
     * @param array  $info
     */
    public static function bindOption(string $class, array $info): void
    {
        self::$commands[$class]['routes'][] = $info;
    }

    /**
     * @param Router $router
     */
    public static function registerTo(Router $router): void
    {
        foreach (self::$commands as $class => $mapping) {
            $group = $mapping['group'];
            $group = CommandHelper::getGroupPrefix($group, $class, $router->getSuffix());

            foreach ($mapping['routes'] as $route) {
                $method = $route['method'];
                $command = $route['command'] ?: $method;

                $router->map($group, $command, [$class, $method], [
                    'alias'     => $route['alias'],
                    'enabled'   => $mapping['enabled'],
                    'coroutine' => $mapping['coroutine'],
                ]);
            }

            // always register default command.
            $defCmd = $router->getDefaultCommand();
            $router->map($group, $defCmd, [$class, $defCmd]);
        }

        // clear data
        self::$commands = [];
    }
}
