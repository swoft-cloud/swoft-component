<?php declare(strict_types=1);

namespace Swoft\Console\Concern;

use Swoft\Console\Console;
use Swoft\Console\Helper\FormatUtil;
use Swoft\Console\Helper\Show;
use Swoft\Console\Output\Output;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Helper\Str;

/**
 * Trait RenderHelpInfoTrait
 * @package Swoft\Console\Concern
 */
trait RenderHelpInfoTrait
{
    /**
     * Display application version
     */
    protected function showVersionInfo(): void
    {
        /** @var Output $output */
        $output = $this->output;

        // version information
        $phpVersion    = \PHP_VERSION;
        $swoftVersion  = \Swoft::VERSION;
        $swooleVersion = \SWOOLE_VERSION;

        // display logo
        $output->writeln(\Swoft::FONT_LOGO);
        // display some information
        $output->writef(
            "PHP: <info>%s</info>, Swoft: <info>%s</info>, Swoole: <info>%s</info>\n",
            $phpVersion, $swoftVersion, $swooleVersion
        );
    }

    /**
     * Display command list of the application
     *
     * @param bool $showLogo
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function showApplicationHelp(bool $showLogo = true): void
    {
        // show logo
        if ($showLogo) {
            Console::colored(\Swoft::FONT_LOGO, 'cyan');
        }

        $script = \input()->getScriptName();
        // built in options
        $internalOptions = FormatUtil::alignOptions(self::$globalOptions);

        // output list
        // \output()->writeList($commandList, 'comment', 'info');
        Show::mList([
            'Usage:'   => "$script <info>{command}</info> [arg0 arg1 arg2 ...] [--opt -v -h ...]",
            'Options:' => $internalOptions,
        ]);

        /* @var Router $router */
        $router = \Swoft::getBean('cliRouter');
        $keyWidth = $router->getKeyWidth();

        Console::startBuffer();
        Console::writeln('<comment>Available Commands:</comment>');

        $grpHandler = function (string $group, array $info) use ($keyWidth) {
            Console::writef(
                '  <info>%s</info>',
                Str::padRight($group, $keyWidth),
                $info['desc'] ?: 'No description message'
            );
        };

        $cmdHandler = function (string $cmdId, array $info) use ($keyWidth) {
            // \var_dump($info);die;
            Console::writef(
                '  <info>%s</info> %s',
                Str::padRight($cmdId, $keyWidth),
                $info['desc'] ?: 'No description message'
            );
        };

        $router->sortedEach($grpHandler, $cmdHandler);

        Console::write("\nMore command information, please use: <cyan>$script {command} -h</cyan>");
        Console::flushBuffer();
    }

    /**
     * Display help, command list of the group
     *
     * @param array $groupInfo
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function showGroupHelp(array $groupInfo): void
    {
        \var_dump($groupInfo);die;
        /* @var Router $router */
        $router = \Swoft::getBean('cliRouter');

        $collector = CommandCollector::getCollector();
        $routes    = $collector[$className]['routes'] ?? [];

        $reflectionClass = new \ReflectionClass($className);
        $classDocument   = $reflectionClass->getDocComment();
        $classDocAry     = DocBlock::getTags($classDocument);
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
            $methodDocAry                = DocBlock::getTags($methodDocument);
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
     * Display help for an command
     *
     * @param string $controllerClass
     * @param string $commandMethod
     * @throws \ReflectionException
     */
    protected function showCommandHelp(string $controllerClass, string $commandMethod): void
    {
        // 反射获取方法描述
        $reflectionClass  = new \ReflectionClass($controllerClass);
        $reflectionMethod = $reflectionClass->getMethod($commandMethod);
        $document         = $reflectionMethod->getDocComment();
        $document         = $this->parseCommentsVars($document, $this->commentsVars());
        $docs             = DocBlock::getTags($document);

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
            $docAry     = DocBlock::getTags($docComment);

            $prefix            = $command['name'];
            $prefix            = $route->getPrefix($prefix, $className);
            $commands[$prefix] = StringHelper::ucfirst($docAry['Description']);
        }

        // sort commands
        \ksort($commands);

        return $commands;
    }
}
