<?php declare(strict_types=1);

namespace Swoft\Console\Input;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Exception\CommandFlagException;
use Toolkit\Cli\Flags;
use function array_map;
use function array_shift;
use function basename;
use function fgets;
use function fwrite;
use function implode;
use function preg_match;
use function trim;
use const STDIN;
use const STDOUT;

/**
 * Class Input - The input information. by parse global var $argv.
 *
 * @since 2.0
 *
 * @Bean("input")
 */
class Input extends AbstractInput
{
    /**
     * The real command ID(group:command)
     * e.g `http:start`
     *
     * @var string
     */
    protected $commandId = '';

    /**
     * @var resource
     */
    protected $inputStream = STDIN;

    /**
     * Input constructor.
     *
     * @param null|array $args
     * @param bool       $parsing
     */
    public function __construct(array $args = null, bool $parsing = true)
    {
        if (null === $args) {
            $args = (array)$_SERVER['argv'];
        }

        $this->pwd    = $this->getPwd();
        $this->tokens = $args;

        $this->scriptFile = array_shift($args);
        $this->fullScript = implode(' ', $args);

        if ($parsing) {
            // list($this->args, $this->sOpts, $this->lOpts) = InputParser::fromArgv($args);
            [$this->args, $this->sOpts, $this->lOpts] = Flags::parseArgv($args);

            // find command name
            $this->findCommand();
        }
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $tokens = array_map(function ($token) {
            if (preg_match('{^(-[^=]+=)(.+)}', $token, $match)) {
                return $match[1] . Flags::escapeToken($match[2]);
            }

            if ($token && $token[0] !== '-') {
                return Flags::escapeToken($token);
            }

            return $token;
        }, $this->tokens);

        return implode(' ', $tokens);
    }

    /**
     * Read input information
     *
     * @param string $question 若不为空，则先输出文本消息
     * @param bool   $nl       true 会添加换行符 false 原样输出，不添加换行符
     *
     * @return string
     */
    public function read(string $question = '', bool $nl = false): string
    {
        if ($question) {
            fwrite(STDOUT, $question . ($nl ? "\n" : ''));
        }

        return trim(fgets($this->inputStream));
    }

    /***********************************************************************************
     * Binding options and arguments
     ***********************************************************************************/

    /**
     * Binding options and arguments by give config
     *
     * @param array $info
     *
     * @throws CommandFlagException
     */
    public function bindingFlags(array $info): void
    {
        // Bind options
        if ($opts = $info['options']) {
            $this->bindingOptions($opts);
        }

        // Bind named argument by index
        if ($args = $info['arguments']) {
            $this->bindingArguments($args);
        }
    }

    /**
     * @param array $opts
     *
     * @throws CommandFlagException
     */
    protected function bindingOptions(array $opts): void
    {
        $sOpts = $this->getSOpts();
        $lOpts = $this->getLOpts();

        foreach ($opts as $name => $opt) {
            $shortName = $opt['short'];
            $inputVal  = $this->getLongOpt($name);

            // Exist short
            if (null === $inputVal && $shortName) {
                $inputVal = $this->getShortOpt($shortName);
            }

            // Exist default value
            if (null === $inputVal && isset($opt['default'])) {
                $inputVal = $opt['default'];
            }

            // Has option value
            if (null !== $inputVal) {
                $lOpts[$name] = $inputVal;

                if ($shortName) {
                    $sOpts[$shortName] = $inputVal;
                }

                // Value is required
            } elseif ($opt['mode'] === Command::OPT_REQUIRED) {
                $short = $shortName ? "(short: {$shortName})" : '';
                throw new CommandFlagException("The option '{$name}'{$short} is required");
            }
        }

        // Save to input
        $this->setLOpts($lOpts, true);
        $this->setSOpts($sOpts, true);
    }

    /**
     * @param array $args
     *
     * @throws CommandFlagException
     */
    protected function bindingArguments(array $args): void
    {
        $index  = 0;
        $values = $this->getArgs();

        foreach ($args as $name => $arg) {
            // Bind value to name
            if (isset($values[$index])) {
                $values[$name] = $values[$index];
                // Bind default value
            } elseif (isset($arg['default'])) {
                $values[$name]  = $arg['default'];
                $values[$index] = $arg['default'];
            }

            // Check arg is required
            if ($arg['mode'] === Command::ARG_REQUIRED && empty($values[$name])) {
                throw new CommandFlagException("The argument '{$name}'(position: {$index}) is required");
            }

            $index++;
        }

        $this->setArgs($values, true);
    }

    /***********************************************************************************
     * Getter/setter methods
     ***********************************************************************************/

    /**
     * @return string
     */
    public function getFullCommand(): string
    {
        return $this->scriptFile . ' ' . $this->command;
    }

    /**
     * @return string
     */
    public function getBinFile(): string
    {
        return $this->scriptFile;
    }

    /**
     * @return string
     */
    public function getBinName(): string
    {
        return $this->getScriptName();
    }

    /**
     * @return string
     */
    public function getScriptName(): string
    {
        return basename($this->scriptFile);
    }

    /**
     * @return resource
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * Get command ID e.g `http:start`
     *
     * @return  string
     */
    public function getCommandId(): string
    {
        return $this->commandId;
    }

    /**
     * Set command ID e.g `http:start`
     *
     * @param string $commandId e.g `http:start`
     *
     * @return void
     */
    public function setCommandId(string $commandId): void
    {
        $this->commandId = $commandId;
    }
}
