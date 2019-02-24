<?php

namespace Swoft\Console;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Console\Concern\RenderHelpInfoTrait;
use Swoft\Console\Contract\ConsoleInterface;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Event;
use function input;
use function output;

/**
 * Class Application
 *
 * @since 2.0
 * @Bean("cliApp")
 */
class Application implements ConsoleInterface
{
    use RenderHelpInfoTrait;

    // {$%s} name -> {name}
    protected const HELP_VAR_LEFT  = '{';
    protected const HELP_VAR_RIGHT = '}';

    /** @var array */
    private static $globalOptions = [
        '--debug'       => 'Setting the application runtime debug level(0 - 4)',
        // '--profile'     => 'Display timing and memory usage information',
        '--no-color'    => 'Disable color/ANSI for message output',
        '-h, --help'    => 'Display this help message',
        '-V, --version' => 'Show application version information',
    ];

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var array
     */
    private $commentsVars = [];

    /**
     * Class constructor.
     */
    public function __construct()
    {
    }

    protected function prepare(): void
    {
        $this->input  = input();
        $this->output = output();

        $this->commentsVars = $this->commentsVars();
    }

    /**
     * Provides parsable parsing variables for command annotations.
     * Can be used in comments in commands.
     *
     * @return array
     */
    public function commentsVars(): array
    {
        // e.g: `more info see {name}:index`
        return [
            // 'name' => self::getName(),
            // 'group' => self::getName(),
            'workDir'     => input()->getPwd(),
            'script'      => input()->getScript(), // bin/app
            'command'     => input()->getCommand(), // demo OR home:test
            'fullCommand' => input()->getFullCommand(),
        ];
    }

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            $this->prepare();

            // get input command
            $inputCommand = $this->input->getCommand();

            if (!$inputCommand) {
                $this->filterSpecialOption();
            } else {
                $this->doRun($inputCommand);
            }
        } catch (\Throwable $e) {
            $this->output->writef(
                "<error>%s</error>\nAt %s line <cyan>%d</cyan>",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
            $this->output->writef("Trace:\n%s", $e->getTraceAsString());
        }
    }

    /**
     * @param string $inputCmd
     * @return void
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function doRun(string $inputCmd): void
    {
        $output = $this->output;
        /* @var Router $router */
        $router = \Swoft::getBean('cliRouter');
        $result = $router->match($inputCmd);

        // Command not found
        if ($result[0] === Router::NOT_FOUND) {
            $names = $router->getAllNames();
            $output->liteError("The entered command '{$inputCmd}' is not exists!");

            // find similar command names by similar_text()
            if ($similar = Arr::findSimilar($inputCmd, $names)) {
                $output->writef("\nMaybe what you mean is:\n    <info>%s</info>", \implode(', ', $similar));
            } else {
                $this->showApplicationHelp(false);
            }
            return;
        }

        $info = $result[1];

        // Only input a group name, display help for the group
        if ($result[0] === Router::ONLY_GROUP) {
            $groupInfo = $router->getGroupInfo($info['group']);
            $this->showGroupHelp($groupInfo);
            return;
        }

        // Display help for a command
        if (\input()->getSameOpt(['h', 'help'])) {
            [$className, $method] = $info['handler'];
            $this->showCommandHelp($className, $method);
            return;
        }

        $this->dispatch($info);
    }

    /**
     * Filter special option. eg: -h, --help, --version
     * @return void
     * @throws \ReflectionException
     */
    private function filterSpecialOption(): void
    {
        // Version option resolution
        if (input()->hasOpt('V') || input()->hasOpt('version')) {
            $this->showVersionInfo();
            return;
        }

        // Display application help, command list
        $this->showApplicationHelp();
    }

    /**
     * @param array $route
     * @return void
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function dispatch(array $route): void
    {
        [$className, $method] = $route['handler'];

        // bind method params
        $bindParams = $this->getBindParams($className, $method);
        $beanObject = \Swoft::getBean($className);

        // blocking running
        if (!$route['coroutine']) {
            $this->beforeExecute(\get_parent_class($beanObject), $method);
            PhpHelper::call([$beanObject, $method], $bindParams);
            $this->afterExecute($method);
            return;
        }

        // coroutine running
        Co::create(function () use ($beanObject, $method, $bindParams) {
            $this->beforeExecute(\get_parent_class($beanObject), $method);
            PhpHelper::call([$beanObject, $method], $bindParams);
            $this->afterExecute($method);
        });
        Event::wait();
    }

    /**
     * Get bounded params
     *
     * @param string $className
     * @param string $methodName
     * @return array
     * @throws \ReflectionException
     */
    private function getBindParams(string $className, string $methodName): array
    {
        /** @var \ReflectionClass $reflectClass */
        $reflectClass  = \Swoft::getReflection($className);
        $reflectMethod = $reflectClass->getMethod($methodName);
        $reflectParams = $reflectMethod->getParameters();

        // binding params
        $bindParams = [];
        foreach ($reflectParams as $key => $reflectParam) {
            $reflectType = $reflectParam->getType();

            // undefined type of the param
            if ($reflectType === null) {
                $bindParams[$key] = null;
                continue;
            }

            // defined type of the param
            $type = $reflectType->getName();
            if ($type === Output::class) {
                $bindParams[$key] = \output();
            } elseif ($type === Input::class) {
                $bindParams[$key] = \input();
            } else {
                $bindParams[$key] = null;
            }
        }

        return $bindParams;
    }

    /**
     * Before execute command
     *
     * @param string $class
     * @param string $command
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function beforeExecute(string $class, string $command): void
    {
        \Swoft::trigger(ConsoleEvent::BEFORE_EXECUTE, $command);
    }

    /**
     * After execute command
     *
     * @param string $command
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function afterExecute(string $command): void
    {
        \Swoft::trigger(ConsoleEvent::AFTER_EXECUTE, $command);
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
        if (false === \strpos($str, self::HELP_VAR_LEFT)) {
            return $str;
        }

        $map = [];

        foreach ($vars as $key => $value) {
            $key = self::HELP_VAR_LEFT . $key . self::HELP_VAR_RIGHT;
            // save
            $map[$key] = $value;
        }

        return $map ? \strtr($str, $map) : $str;
    }
}
