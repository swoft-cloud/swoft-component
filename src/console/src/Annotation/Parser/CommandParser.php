<?php

namespace Swoft\Console\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Helper\DocBlock;
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
     * @var array[]
     * [
     *  class => [
     *      group => group name,
     *      commands => [
     *          method => [
     *              name      => command name
     *              options   => [],
     *              arguments => [],
     *          ]
     *      ]
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
            'aliases'   => $annotation->getAliases(),
            'enabled'   => $annotation->isEnabled(),
        ];

        return [$class, $class, Bean::SINGLETON, ''];
    }

    /**
     * @param string $class
     * @param string $method
     * @param array  $route
     */
    public static function addRoute(string $class, string $method, array $route): void
    {
        self::$commands[$class]['commands'][$method] = $route;
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $argName
     * @param array  $info
     */
    public static function bindArgument(string $class, string $method, string $argName, array $info): void
    {
        $cmdInfo = self::$commands[$class]['commands'][$method];
        // add argument info
        $cmdInfo['arguments'][$argName] = $info;
        // re-setting
        self::$commands[$class]['commands'][$method] = $cmdInfo;
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $optName
     * @param array  $info
     */
    public static function bindOption(string $class, string $method, string $optName, array $info): void
    {
        $cmdInfo = self::$commands[$class]['commands'][$method];
        // add option info
        $cmdInfo['options'][$optName] = $info;
        // re-setting
        self::$commands[$class]['commands'][$method] = $cmdInfo;
    }

    /**
     * @param Router $router
     * @throws \ReflectionException
     */
    public static function registerTo(Router $router): void
    {
        $maxLen = 12;
        $groups = [];
        // $defCmd = $router->getDefaultCommand();
        // default description
        $defDesc = 'No description message';

        foreach (self::$commands as $class => $mapping) {
            $names = [];
            $group = $mapping['group'];
            // set group name aliases
            $router->setGroupAliases($group, $mapping['aliases']);

            $refInfo = \Swoft::getReflection($class);

            foreach ($mapping['commands'] as $method => $route) {
                // $method = $route['method'];
                $command = $route['command'];
                $idLen   = \strlen($group . $command);

                if ($idLen > $maxLen) {
                    $maxLen = $idLen;
                }
                $cmdDesc = $route['desc'];

                if (!$cmdDesc && !empty($refInfo['methods'][$method]['comments'])) {
                    $cmdDesc = DocBlock::firstLine($refInfo['methods'][$method]['comments']);
                }

                $router->map($group, $command, [$class, $method], [
                    'desc'      => $cmdDesc ? \ucfirst($cmdDesc) : $defDesc,
                    'alias'     => $route['alias'],
                    'aliases'   => $route['aliases'],
                    'enabled'   => $mapping['enabled'],
                    // 'coroutine' => $mapping['coroutine'],
                    // options
                    'options'   => $route['options'] ?? [],
                    // arguments
                    'arguments' => $route['arguments'] ?? [],
                ]);

                $names[] = $command;
            }

            // always register default command.
            // $router->map($group, $defCmd, [$class, $defCmd]);

            $groupDesc = $mapping['desc'];
            if (!$groupDesc && !empty($refInfo['comments'])) {
                $groupDesc = DocBlock::firstLine($refInfo['comments']);
            }

            $groups[$group] = [
                'names'   => $names,
                'desc'    => $groupDesc ? \ucfirst($groupDesc) : $defDesc,
                'class'   => $class,
                'alias'   => $mapping['alias'],
                'aliases' => $mapping['aliases'],
            ];
        }

        $router->setGroups($groups);
        // +1 because router->delimiter
        $router->setKeyWidth($maxLen + 1);
        // clear data
        self::$commands = [];
    }
}
