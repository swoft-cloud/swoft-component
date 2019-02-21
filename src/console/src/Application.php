<?php

namespace Swoft\Console;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Bean\Collector\CommandCollector;
use Swoft\Console\Contract\ConsoleInterface;
use Swoft\Console\Helper\DocBlockHelper;
use Swoft\Console\Router\Dispatcher;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Helper\StringHelper;
use function input;

/**
 * Class Application
 * @since 2.0
 * @package Swoft\Console
 *
 * @Bean("cliApp")
 */
class Application implements ConsoleInterface
{
    // name -> {name}
    protected const COMMENTS_VAR = '{%s}'; // '{$%s}';

    /**
     * Class constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            $this->doRun();
        } catch (\Throwable $e) {
            \output()->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            \output()->writeln(sprintf("Trace:\n%s", $e->getTraceAsString()), true, true);
        }
    }

    /**
     * 为命令注解提供可解析解析变量. 可以在命令的注释中使用
     * @return array
     */
    public function commentsVars(): array
    {
        // e.g: `more info see {name}:index`
        return [
            // 'name' => self::getName(),
            // 'group' => self::getName(),
            'workDir'     => \input()->getPwd(),
            'script'      => \input()->getScript(), // bin/app
            'command'     => \input()->getCommand(), // demo OR home:test
            'fullCommand' => \input()->getFullCommand(),
        ];
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function doRun(): void
    {
        if (!$command = \input()->getCommand()) {
            $this->baseCommand();
            return;
        }

        /* @var Router $router */
        $router = \Swoft::getBean('consoleRouter');

        if (!$result = $router->match($command)) {
            \output()->colored("The entered command does not exist! command = $command", 'error');
            $this->showCommandList(false);
            return;
        }

        $handler = $result['handler'];
        // parse
        [$className, $method] = $result['handler'];

        if ($router->isDefault($method)) {
            $this->indexCommand($className);
            return;
        }

        // show help
        if (input()->getSameOpt(['h', 'help'])) {
            $this->showCommandHelp($className, $method);
            return;
        }

        /* @var Dispatcher $dispatcher */
        $dispatcher = \Swoft::getBean(Dispatcher::class);
        $dispatcher->dispatch($handler);
    }

    /**
     * @param string $className
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @return void
     */
    private function indexCommand(string $className): void
    {
        /* @var Router $router */
        $router = \Swoft::getBean('consoleRouter');

        $collector = CommandCollector::getCollector();
        $routes    = $collector[$className]['routes'] ?? [];

        $reflectionClass = new \ReflectionClass($className);
        $classDocument   = $reflectionClass->getDocComment();
        $classDocAry     = DocBlockHelper::getTags($classDocument);
        $classDesc       = $classDocAry['Description'];

        $methodCommands = [];

        foreach ($routes as $route) {
            $mappedName = $route['mappedName'];
            $methodName = $route['methodName'];
            $mappedName = empty($mappedName) ? $methodName : $mappedName;

            if ($methodName === 'init') {
                continue;
            }

            if ($router->isDefaultCommand($methodName)) {
                continue;
            }
            $reflectionMethod            = $reflectionClass->getMethod($methodName);
            $methodDocument              = $reflectionMethod->getDocComment();
            $methodDocAry                = DocBlockHelper::getTags($methodDocument);
            $methodCommands[$mappedName] = $methodDocAry['Description'];
        }

        // 命令显示结构
        $commandList = [
            'Description:' => [$classDesc],
            'Usage:'       => [\input()->getCommand() . ':{command} [arguments] [options]'],
            'Commands:'    => $methodCommands,
            'Options:'     => [
                '-h, --help' => 'Show help of the command group or specified command action',
            ],
        ];

        \output()->writeList($commandList);
    }

    /**
     * the help of group
     *
     * @param string $controllerClass
     * @param string $commandMethod
     * @throws \ReflectionException
     */
    private function showCommandHelp(string $controllerClass, string $commandMethod): void
    {
        // 反射获取方法描述
        $reflectionClass  = new \ReflectionClass($controllerClass);
        $reflectionMethod = $reflectionClass->getMethod($commandMethod);
        $document         = $reflectionMethod->getDocComment();
        $document         = $this->parseCommentsVars($document, $this->commentsVars());
        $docs             = DocBlockHelper::getTags($document);

        $commands = [];

        // 描述
        if (isset($docs['Description'])) {
            $commands['Description:'] = explode("\n", $docs['Description']);
        }

        // 使用
        if (isset($docs['Usage'])) {
            $commands['Usage:'] = $docs['Usage'];
        }

        // 参数
        if (isset($docs['Arguments'])) {
            // $arguments = $this->parserKeyAndDesc($docs['Arguments']);
            $commands['Arguments:'] = $docs['Arguments'];
        }

        // 选项
        if (isset($docs['Options'])) {
            // $options = $this->parserKeyAndDesc($docs['Options']);
            $commands['Options:'] = $docs['Options'];
        }

        // 实例
        if (isset($docs['Example'])) {
            $commands['Example:'] = [$docs['Example']];
        }

        \output()->writeList($commands);
    }

    /**
     * show all commands for the console app
     *
     * @param bool $showLogo
     * @throws \ReflectionException
     */
    public function showCommandList(bool $showLogo = true): void
    {
        $commands = $this->parserCmdAndDesc();

        $commandList              = [];
        $script                   = \input()->getFullScript();
        $commandList['Usage:']    = ["php $script {command} [arguments] [options]"];
        $commandList['Commands:'] = $commands;
        $commandList['Options:']  = [
            '-h, --help'    => 'Display help information',
            '-v, --version' => 'Display version information',
        ];

        // show logo
        if ($showLogo) {
            \output()->writeln(\Swoft::FONT_LOGO);
        }

        // output list
        \output()->writeList($commandList, 'comment', 'info');
    }

    /**
     * version
     */
    private function showVersion(): void
    {
        // 当前版本信息
        $swoftVersion  = \Swoft::VERSION;
        $phpVersion    = \PHP_VERSION;
        $swooleVersion = \SWOOLE_VERSION;

        // 显示面板
        \output()->writeLogo();
        \output()->writeln(
            "swoft: <info>$swoftVersion</info>, php: <info>$phpVersion</info>, swoole: <info>$swooleVersion</info>\n",
            true
        );
    }

    /**
     * the command list
     *
     * @return array
     * @throws \ReflectionException
     */
    private function parserCmdAndDesc(): array
    {
        $commands  = [];
        $collector = CommandCollector::getCollector();

        /* @var \Swoft\Console\Router\HandlerMapping $route */
        $route = App::getBean('commandRoute');

        foreach ($collector as $className => $command) {
            if (!$command['enabled']) {
                continue;
            }

            $rc         = new \ReflectionClass($className);
            $docComment = $rc->getDocComment();
            $docAry     = DocBlockHelper::getTags($docComment);

            $prefix            = $command['name'];
            $prefix            = $route->getPrefix($prefix, $className);
            $commands[$prefix] = StringHelper::ucfirst($docAry['Description']);
        }

        // sort commands
        ksort($commands);

        return $commands;
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function baseCommand()
    {
        // 版本命令解析
        if (input()->hasOpt('v') || input()->hasOpt('version')) {
            $this->showVersion();

            return;
        }

        // 显示命令列表
        $this->showCommandList();
    }

    /**
     * 替换注释中的变量为对应的值
     * @param string $str
     * @param array  $vars
     * @return string
     */
    protected function parseCommentsVars(string $str, array $vars): string
    {
        // not use vars
        if (false === strpos($str, '{')) {
            return $str;
        }

        $map = [];

        foreach ($vars as $key => $value) {
            $key       = sprintf(self::COMMENTS_VAR, $key);
            $map[$key] = $value;
        }

        return $map ? strtr($str, $map) : $str;
    }
}
