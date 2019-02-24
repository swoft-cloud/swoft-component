<?php

namespace Swoft\Console\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Contract\RouterInterface;

/**
 * Class Router
 *
 * @since 2.0
 * @Bean("cliRouter")
 */
class Router implements RouterInterface
{
    // Default commands
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
     * The default group of command
     * @var string
     */
    private $defaultGroup = 'http';

    /**
     * The default command
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
     *      'handler'  => [group class, command method],
     *      'metadata' => [
     *          'aliases'   => [],
     *          'options'   => [],
     *          'arguments' => [],
     *      ],
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
     *      class => Group class,
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

        $options['handler'] = $handler;

        $this->routes[$cmdID] = $options;
    }

    /**
     * Match route by input command
     *
     * @param array $params [$route]
     * @return array
     * [
     *  status, info(array)
     * ]
     */
    public function match(...$params): array
    {
        $delimiter = $this->delimiter;
        $inputCmd  = \trim($params[0], "$delimiter ");

        if (\in_array($inputCmd, self::DEFAULT_METHODS, true)) {
            $group   = $this->defaultGroup;
            $command = $this->resolveGroupAlias($inputCmd);

            // only a group name
        } elseif (\strpos($inputCmd, $delimiter) === false) {
            return [
                self::ONLY_GROUP,
                [
                    'group' => $this->resolveGroupAlias($inputCmd),
                ]
            ];
        } else {
            $nameList = \explode($delimiter, $inputCmd, 2);

            if (\count($nameList) === 2) {
                [$group, $command] = $nameList;
                // resolve command alias
                $command = $this->resolveCommandAlias($command);
            } else {
                $command = '';
                // $command = $this->defaultCommand;
                $group = $nameList[0];
            }
        }

        // return [$group, $command ?: $this->defaultCommand];
        $group = $this->resolveGroupAlias($group);
        // build command ID
        $commandID = $this->buildCommandID($group, $command);

        if (isset($this->routes[$commandID])) {
            $info = $this->routes[$commandID];
            return [self::FOUND, $info];
        }

        if (isset($this->groups[$group])) {
            return [
                self::ONLY_GROUP,
                [
                    'group' => $group,
                ]
            ];
        }

        return [self::NOT_FOUND];
    }

    /**
     * @param callable $grpFunc function(string $group, array $info)
     * @param callable $cmdFunc function(string $id, array $info)
     */
    public function sortedEach(callable $grpFunc, callable $cmdFunc): void
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
     * @param string $name
     * @return bool
     */
    public function isGroup(string $name): bool
    {
        return isset($this->groups[$name]);
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
     * @param string $name
     * @return string
     */
    public function resolveGroupAlias(string $name): string
    {
        return $this->groupAliases[$name] ?? $name;
    }

    /**
     * @param string $name
     * @return string
     */
    public function resolveCommandAlias(string $name): string
    {
        return $this->commandAliases[$name] ?? $name;
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
     * Get all name for search
     * @return array
     */
    public function getAllNames(): array
    {
        return \array_merge(\array_keys($this->getGroupAliases()), \array_keys($this->groups));
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
     * @param string $name
     * @return array
     */
    public function getGroupInfo(string $name): array
    {
        return $this->groups[$name] ?? [];
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
