<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Console\Router;

use Swoft\Http\Message\Router\HandlerMappingInterface;

class HandlerMapping implements HandlerMappingInterface
{
    const DEFAULT_METHODS = [
        'start',
        'reload',
        'stop',
        'restart',
    ];

    protected $suffix = 'Command';

    protected $defaultGroup = 'server';

    protected $defaultCommand = 'index';

    protected $delimiter = ':';

    protected $routes = [];

    /**
     * @throws \InvalidArgumentException
     */
    public function getHandler(...$params): array
    {
        list($group, $command) = $this->getGroupAndCommand();
        $route = $this->getCommandString($group, $command);

        return $this->match($route);
    }

    /**
     * Auto register routes
     */
    public function register(array $commandMapping)
    {
        foreach ($commandMapping as $className => $mapping) {
            $prefix = $mapping['name'];
            $routes = $mapping['routes'];
            $coroutine = $mapping['coroutine'];
            $server = $mapping['server'];
            $prefix = $this->getPrefix($prefix, $className);
            $this->registerRoute($className, $routes, $prefix, $coroutine, $server);
        }
    }

    public function isDefaultCommand(string $command): bool
    {
        return $command === $this->defaultCommand;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function match(string $route): array
    {
        if (!isset($this->routes[$route])) {
            return [];
        }

        return $this->routes[$route];
    }

    /**
     * Get command from class name
     */
    public function getPrefix(string $prefix, string $className): string
    {
        // the prefix of annotation is exist
        if (! empty($prefix)) {
            return $prefix;
        }

        // the prefix of annotation is empty
        $reg = '/^.*\\\(\w+)' . $this->suffix . '$/';
        $prefix = '';

        if ($result = \preg_match($reg, $className, $match)) {
            $prefix = \lcfirst($match[1]);
        }

        return $prefix;
    }

    private function getGroupAndCommand(): array
    {
        $cmd = \input()->getCommand();
        if (\in_array($cmd, self::DEFAULT_METHODS, true)) {
            return [$this->defaultGroup, $cmd];
        }

        $commandAry = \explode($this->delimiter, $cmd);
        if (\count($commandAry) >= 2) {
            list($group, $command) = $commandAry;
        } else {
            list($group) = $commandAry;
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
     * Register one route
     */
    private function registerRoute(string $className, array $routes, string $prefix, bool $coroutine, $server)
    {
        foreach ($routes as $route) {
            $mappedName = $route['mappedName'];
            $methodName = $route['methodName'];
            if (empty($mappedName)) {
                $mappedName = $methodName;
            }

            $commandKey = $this->getCommandString($prefix, $mappedName);
            $this->routes[$commandKey] = [$className, $methodName, $coroutine, $server];
        }

        $commandKey = $this->getCommandString($prefix, $this->defaultCommand);
        $this->routes[$commandKey] = [$className, $this->defaultCommand];
    }

    private function getCommandString(string $group, string $command): string
    {
        return \sprintf('%s%s%s', $group, $this->delimiter, $command);
    }
}
