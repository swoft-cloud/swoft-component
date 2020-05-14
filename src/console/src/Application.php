<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Console\Concern\RenderHelpInfoTrait;
use Swoft\Console\Contract\ConsoleInterface;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Concern\DataPropertyTrait;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\ObjectHelper;
use Throwable;
use function array_merge;
use function implode;
use function strpos;
use function strtr;
use function trim;
use function ucfirst;

/**
 * Class Application
 *
 * @since 2.0
 * @Bean("cliApp")
 */
class Application implements ConsoleInterface
{
    use RenderHelpInfoTrait, DataPropertyTrait;

    // {$%s} name -> {name}
    protected const HELP_VAR_LEFT  = '{';

    protected const HELP_VAR_RIGHT = '}';

    /** @var array */
    private static $globalOptions = [
        '--debug'       => 'Setting the application runtime debug level(0 - 4)',
        // '--profile'     => 'Display timing and memory usage information',
        '--no-color'    => 'Disable color/ANSI for message output',
        '-h, --help'    => 'Display help message for application or command',
        '-V, --version' => 'Display application version information',
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
     * @var string
     */
    private $name = 'Swoft 2.0';

    /**
     * @var string
     */
    private $version = '2.0.0';

    /**
     * @var string
     */
    private $description = 'Swoft - An PHP micro-service coroutine framework';

    /**
     * Console application logo text
     *
     * @var string
     */
    private $logoText = '';

    /**
     * @var array
     */
    private $commentsVars = [];

    /**
     * Class constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        ObjectHelper::init($this, $options);
    }

    /**
     * Provides parsable parsing variables for command annotations.
     * Can be used in comments in commands.
     *
     * @return array
     */
    public function commentsVars(): array
    {
        $script  = $this->input->getScriptFile();
        $fullCmd = $this->input->getFullCommand();

        return [
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
            // 'group' => self::getName(),
            'workDir'     => $this->input->getPwd(),
            'script'      => $script, // bin/app
            'binFile'     => $script,
            'binName'     => $this->input->getScriptName(),
            'command'     => $this->input->getCommand(), // demo OR home:test
            'fullCmd'     => $fullCmd,
            'fullCommand' => $fullCmd,
        ];
    }

    protected function prepare(): void
    {
        $this->input  = Swoft::getBean('input');
        $this->output = Swoft::getBean('output');

        // load builtin comments vars
        $this->setCommentsVars($this->commentsVars());
    }

    /**
     * @return void
     */
    public function run(): void
    {
        try {
            // Prepare for run
            $this->prepare();

            Swoft::trigger(ConsoleEvent::RUN_BEFORE, $this);

            // Get input command
            if (!$inputCommand = $this->input->getCommand()) {
                $this->filterSpecialOption();
            } else {
                $this->doRun($inputCommand);
            }

            Swoft::trigger(ConsoleEvent::RUN_AFTER, $this, $inputCommand);
        } catch (Throwable $e) {
            /** @var ConsoleErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(ConsoleErrorDispatcher::class);

            // Handle request error
            $errDispatcher->run($e);
        }
    }

    /**
     * @param string $inputCmd
     *
     * @return void
     * @throws Throwable
     */
    protected function doRun(string $inputCmd): void
    {
        $output = $this->output;
        /* @var Router $router */
        $router = Swoft::getBean('cliRouter');
        $result = $router->match($inputCmd);

        // Command not found
        if ($result[0] === Router::NOT_FOUND) {
            $names = $router->getAllNames();
            $output->liteError("The entered command '{$inputCmd}' is not exists!");

            // find similar command names by similar_text()
            if ($similar = Arr::findSimilar($inputCmd, $names)) {
                $output->writef("\nMaybe what you mean is:\n    <info>%s</info>", implode(', ', $similar));
            } else {
                $this->showApplicationHelp(false);
            }
            return;
        }

        $info  = $result[1];
        $group = $info['group'];
        $this->addCommentsVar('groupName', $group);

        // Only input a group name, display help for the group
        if ($result[0] === Router::ONLY_GROUP) {
            // Has error command
            if ($cmd = $info['cmd']) {
                $output->error("Command '{$cmd}' is not exist in group: {$group}");
            }

            $this->showGroupHelp($group);
            return;
        }

        // Display help for a command
        if ($this->input->getSameOpt(['h', 'help'])) {
            $this->showCommandHelp($info);
            return;
        }

        // Parse default options and arguments
        $this->input->parseFlags($info, true);
        $this->input->setCommandId($info['cmdId']);

        Swoft::triggerByArray(ConsoleEvent::DISPATCH_BEFORE, $this, $info);

        // Call command handler
        /** @var ConsoleDispatcher $dispatcher */
        $dispatcher = Swoft::getSingleton('cliDispatcher');
        $dispatcher->dispatch($info);

        Swoft::triggerByArray(ConsoleEvent::DISPATCH_AFTER, $this, $info);
    }

    /**
     * Filter special option. eg: -h, --help, --version
     *
     * @return void
     */
    private function filterSpecialOption(): void
    {
        // Version option resolution
        if ($this->input->getSameOpt(['V', 'version'], false)) {
            $this->showVersionInfo();
            return;
        }

        // Display application help, command list
        $this->showApplicationHelp(false);
    }

    /**
     * Replace the variable in the comment with the corresponding value
     *
     * @param string $str
     * @param array  $vars
     *
     * @return string
     */
    protected function parseCommentsVars(string $str, array $vars): string
    {
        // not use vars
        if (false === strpos($str, self::HELP_VAR_LEFT)) {
            return $str;
        }

        $map = [];
        foreach ($vars as $key => $value) {
            $key = self::HELP_VAR_LEFT . $key . self::HELP_VAR_RIGHT;
            // save
            $map[$key] = $value;
        }

        return $map ? strtr($str, $map) : $str;
    }

    /**
     * @return array
     */
    public function getCommentsVars(): array
    {
        return $this->commentsVars;
    }

    /**
     * @param array $vars
     */
    public function setCommentsVars(array $vars): void
    {
        if ($vars) {
            $this->commentsVars = array_merge($this->commentsVars, $vars);
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addCommentsVar(string $name, string $value): void
    {
        $this->commentsVars[$name] = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ? ucfirst($this->description) : '';
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    /**
     * @return string
     */
    public function getLogoText(): string
    {
        return $this->logoText;
    }

    /**
     * @param string $logoText
     */
    public function setLogoText(string $logoText): void
    {
        $this->logoText = $logoText;
    }
}
