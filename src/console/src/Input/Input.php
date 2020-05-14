<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Input;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Exception\CommandFlagException;
use Swoft\Console\FlagType;
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

        $this->tokens = $args;

        $this->scriptFile = array_shift($args);
        $this->fullScript = implode(' ', $args);

        // find command name, other is flags
        $this->flags = $this->findCommand($args);
        $this->pwd   = $this->getPwd();

        if ($parsing) {
            // list($this->args, $this->sOpts, $this->lOpts) = InputParser::fromArgv($args);
            [$this->args, $this->sOpts, $this->lOpts] = Flags::parseArgv($this->flags);
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
     * @param string $question The message before read input
     * @param bool   $nl       Add new line. True: will add new line. False: direct output.
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
     * Re-parse flags by command info
     *
     * @param array $info
     * @param bool  $binding
     *
     * @throws CommandFlagException
     */
    public function parseFlags(array $info, bool $binding = false): void
    {
        $config = [];

        // Parse options(find bool and array options)
        if ($cmdOpts = $info['options']) {
            foreach ($cmdOpts as $name => $opt) {
                if ($opt['type'] === FlagType::BOOL) {
                    $config['boolOpts'][] = $name;
                } elseif ($opt['type'] === FlagType::ARRAY) {
                    $config['arrayOpts'][] = $name;
                }
            }
        }

        // re-parsing
        if ($this->flags) {
            [$this->args, $this->sOpts, $this->lOpts] = Flags::parseArgv($this->flags, $config);

            // Binding
            if ($binding) {
                $this->bindingFlags($info);
            }
        }
    }

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
     * @param array $cmdOpts The command options definition
     *
     * @throws CommandFlagException
     */
    protected function bindingOptions(array $cmdOpts): void
    {
        foreach ($cmdOpts as $name => $opt) {
            $shortName = $opt['short'];
            $inputVal  = $this->getLongOpt($name);

            // Exist short name
            if (null === $inputVal && $shortName) {
                $inputVal = $this->getShortOpt($shortName);
            }

            // Exist default value
            if (null === $inputVal && isset($opt['default'])) {
                $inputVal = $opt['default'];
            }

            // Has option value
            if (null !== $inputVal) {
                $typedValue = FlagType::convertType($opt['type'], $inputVal);

                $this->lOpts[$name] = $typedValue;
                if ($shortName) {
                    $this->sOpts[$shortName] = $typedValue;
                }

                // Value is required
            } elseif ($opt['mode'] === Command::OPT_REQUIRED) {
                $short = $shortName ? "(short: {$shortName})" : '';
                throw new CommandFlagException("The option '{$name}'{$short} is required");
            }
        }
    }

    /**
     * @param array $cmdArgs The command options definition
     *
     * @throws CommandFlagException
     */
    protected function bindingArguments(array $cmdArgs): void
    {
        $index  = 0;
        $values = $this->getArgs();

        foreach ($cmdArgs as $name => $arg) {
            // Bind value to name
            if (isset($values[$index])) {
                $typeValue = FlagType::convertType($arg['type'], $values[$index]);
                // Re-set strict type value
                $this->args[$name] = $this->args[$index] = $typeValue;
            // Bind default value
            } elseif (isset($arg['default'])) {
                $typeValue = FlagType::convertType($arg['type'], $arg['default']);
                // Re-set strict type value
                $this->args[$name] = $this->args[$index] = $typeValue;
            }

            // Check arg is required
            if ($arg['mode'] === Command::ARG_REQUIRED && empty($values[$index])) {
                throw new CommandFlagException("The argument '{$name}'(position: {$index}) is required");
            }

            $index++;
        }
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
