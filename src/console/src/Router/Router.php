<?php

namespace Swoft\Console\Router;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Router
 * @since 2.0
 * @package Swoft\Console\Router
 *
 * @Bean("cliRouter")
 */
class Router //implements HandlerMappingInterface
{
    /**
     * default commands
     */
    public const DEFAULT_METHODS = [
        'start',
        'reload',
        'stop',
        'restart',
    ];

    /**
     * @var string
     */
    private $suffix = 'Command';

    /**
     * the default group of command
     * @var string
     */
    private $defaultGroup = 'server';

    /**
     * the default command
     * @var string
     */
    private $defaultCommand = 'index';

    /**
     * the delimiter
     *
     * @var string
     */
    private $delimiter = ':';

    /**
     * Use for render help info panel
     *
     * @var int
     */
    private $keyWidth = 12;

    /**
     * @var array
     * [
     *  // route ID => route info.
     *  'group:cmd' => [
     *      'handler' => [command class, method],
     *      'options' => [],
     *  ]
     * ]
     */
    private $routes = [];

    /**
     * Will record the relationship data of group and commands
     *
     * @var array
     * [
     *  group1 => [
     *      desc => 'description',
     *      names => [cmd1, cmd2],
     *      aliases => [],
     *  ],
     *  ...
     * ]
     */
    private $groups = [];

    /**
     * @var array [alias => real name]
     */
    private $groupAliases = [];

    /**
     * @var array [alias => real name]
     */
    private $commandAliases = [];

    /**
     * @param string         $group
     * @param string         $command
     * @param mixed|callable $handler
     * @param array          $options
     */
    public function map(string $group, string $command, $handler, array $options = []): void
    {
        $cmdID = $this->buildCommandID($group, $command);

        if ($aliases = $options['aliases'] ?? []) {
            $this->setCommandAliases($command, $aliases);
        }

        $this->routes[$cmdID] = [
            'handler' => $handler,
            'options' => $options,
        ];
    }

    /**
     * Match route
     *
     * @param array $params [$route]
     * @return array
     */
    public function match(...$params): array
    {
        [$group, $command] = $this->getGroupAndCommand($params[0]);
        // build command ID
        $commandID = $this->buildCommandID($group, $command);

        return $this->routes[$commandID] ?? [];
    }

    /**
     * @param string $cmd
     * @return array
     */
    private function getGroupAndCommand(string $cmd): array
    {
        if (\in_array($cmd, self::DEFAULT_METHODS, true)) {
            return [$this->defaultGroup, $cmd];
        }

        $delimiter  = $this->delimiter;
        $commandAry = \explode($delimiter, \trim($cmd, "$delimiter "), 2);
        if (\count($commandAry) === 2) {
            [$group, $command] = $commandAry;
        } else {
            [$group] = $commandAry;
            $command = '';
        }

        if (empty($group)) {
            return [];
        }

        return [$group, $command ?: $this->defaultCommand];
    }

    /**
     * @param callable $cmdFunc
     * @param callable $grpFunc
     */
    public function sortedEach(callable $cmdFunc, callable $grpFunc): void
    {
        $groups = $this->groups;
        \ksort($groups);

        foreach ($groups as $group => $info) {
            $names = $info['names'];
            \ksort($names);
            unset($info['names']);

            // call group handle func
            $grpFunc($group, $info);

            foreach ($names as $name) {
                $id = $this->buildCommandID($group, $name);
                // call command handle func
                $cmdFunc($id, $this->routes[$id]);
            }
        }
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getGroupName(string $alias): string
    {
        return $this->groupAliases[$alias] ?? $alias;
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getCommandName(string $alias): string
    {
        return $this->commandAliases[$alias] ?? $alias;
    }

    /**
     * @param string $command
     * @return bool
     */
    public function isDefault(string $command): bool
    {
        return $command === $this->defaultCommand;
    }

    /**
     * command ID = group + : + command
     * @param string $group
     * @param string $command
     * @return string
     */
    public function buildCommandID(string $group, string $command): string
    {
        if ($group) {
            return \sprintf('%s%s%s', $group, $this->delimiter, $command);
        }

        return $command;
    }

    /**
     * @param string $group
     * @param array  $aliases
     */
    public function setGroupAliases(string $group, array $aliases): void
    {
        foreach ($aliases as $alias) {
            $this->setGroupAlias($group, $alias);
        }
    }

    /**
     * @param string $group
     * @param string $alias
     */
    public function setGroupAlias(string $group, string $alias): void
    {
        if ($group && $alias) {
            $this->groupAliases[$alias] = $group;
        }
    }

    /**
     * @param string $command
     * @param array  $aliases
     */
    public function setCommandAliases(string $command, array $aliases): void
    {
        foreach ($aliases as $alias) {
            $this->setCommandAlias($command, $alias);
        }
    }

    /**
     * @param string $command
     * @param string $alias
     */
    public function setCommandAlias(string $command, string $alias): void
    {
        if ($command && $alias) {
            $this->commandAliases[$alias] = $command;
        }
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return string
     */
    public function getDefaultCommand(): string
    {
        return $this->defaultCommand;
    }

    /**
     * @return array
     */
    public function getGroupAliases(): array
    {
        return $this->groupAliases;
    }

    /**
     * @return array
     */
    public function getCommandAliases(): array
    {
        return $this->commandAliases;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * @return int
     */
    public function getKeyWidth(): int
    {
        return $this->keyWidth;
    }

    /**
     * @param int $keyWidth
     */
    public function setKeyWidth(int $keyWidth): void
    {
        $this->keyWidth = $keyWidth;
    }
}
