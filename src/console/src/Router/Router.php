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
     * @var array
     * [
     *  route => [
     *      'handler' => [command class, method],
     *      'options' => [],
     *  ]
     * ]
     */
    private $routes = [];

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
        $route = $this->buildRoute($group, $command);

        if ($alias = $options['alias'] ?? '') {
            $this->setCommandAlias($command, $alias);
        }

        $this->routes[$route] = [
            'handler' => $handler,
            'options' => $options,
        ];
    }

    /**
     * @param string $group
     * @param string $alias
     */
    public function setGroupAlias(string $group, string $alias): void
    {
        $this->groupAliases[$alias] = $group;
    }

    /**
     * @param string $command
     * @param string $alias
     */
    public function setCommandAlias(string $command, string $alias): void
    {
        $this->commandAliases[$alias] = $command;
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
        // build
        $route = $this->buildRoute($group, $command);

        return $this->routes[$route] ?? [];
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

        if (empty($command)) {
            $command = $this->defaultCommand;
        }

        return [$group, $command];
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
     * @param string $group
     * @param string $command
     * @return string
     */
    public function buildRoute(string $group, string $command): string
    {
        if ($group) {
            return \sprintf('%s%s%s', $group, $this->delimiter, $command);
        }

        return $command;
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
}
