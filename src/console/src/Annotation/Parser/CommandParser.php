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
     *      group   => group name,
     *      desc    => group description,
     *      alias   => group alias string,
     *      aliases => [], // group alias list
     *      options => [], // group options, will apply for all commands
     *      commands => [
     *          method => [
     *              name      => command name,
     *              desc      => command description,
     *              alias     => command alias string,
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
        $group = $annotation->getName() ?: Str::getClassName($class, 'Command');

        // add route for the command controller
        self::$commands[$class] = [
            'group'     => $group,
            'desc'      => $annotation->getDesc(),
            'alias'     => $annotation->getAlias(),
            'aliases'   => $annotation->getAliases(),
            'enabled'   => $annotation->isEnabled(),
            'coroutine' => $annotation->isCoroutine(),
        ];

        return [$class, $class, Bean::SINGLETON, ''];
    }

    public static function addHandler()
    {

    }

    /**
     * @param string $class
     * @param string $method
     * @param array  $route
     */
    public static function addRoute(string $class, string $method, array $route): void
    {
        // init some keys
        $route['options']   = [];
        $route['arguments'] = [];
        // save
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
        // if not 'commands', is bind group options.
        if (!isset(self::$commands[$class]['commands'])) {
            self::$commands[$class]['options'][$optName] = $info;
            return;
        }

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
            $grpOpts = $mapping['options'] ?? [];

            foreach ($mapping['commands'] as $method => $route) {
                // $method = $route['method'];
                $cmdDesc = $route['desc'];
                $command = $route['command'];
                $idLen   = \strlen($group . $command);

                if ($idLen > $maxLen) {
                    $maxLen = $idLen;
                }

                if (!$cmdDesc && !empty($refInfo['methods'][$method]['comments'])) {
                    $cmdDesc = DocBlock::firstLine($refInfo['methods'][$method]['comments']);
                }

                $route['group']   = $group;
                $route['desc']    = $cmdDesc ? \ucfirst($cmdDesc) : $defDesc;
                $route['options'] = self::mergeOptions($grpOpts, $route['options']);
                // append group option
                $route['enabled']   = $mapping['enabled'];
                $route['coroutine'] = $mapping['coroutine'];

                $router->map($group, $command, [$class, $method], $route);

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

    /**
     * @param array $grpOptions
     * @param array $cmdOptions
     * @return array
     */
    private static function mergeOptions(array $grpOptions, array $cmdOptions): array
    {
        if ($grpOptions) {
            return \array_merge($grpOptions, $cmdOptions);
        }

        return $cmdOptions;
    }
}
