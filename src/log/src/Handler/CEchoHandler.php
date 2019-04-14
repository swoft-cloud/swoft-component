<?php declare(strict_types=1);


namespace Swoft\Log\Handler;


use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Toolkit\Cli\Color;
use Toolkit\Cli\ColorTag;

/**
 * Class CEchoHandler
 *
 * @since 2.0
 */
class CEchoHandler extends AbstractProcessingHandler
{
    /**
     * All styles
     */
    public const STYLES = [
        Logger::INFO    => 'green',
        Logger::DEBUG   => 'cyan',
        Logger::WARNING => 'yellow',
        Logger::ERROR   => 'red',
    ];

    /**
     * Write log levels
     *
     * @var array
     */
    protected $levels = [];

    /**
     * Write log to command line
     *
     * @var bool
     */
    private $output = true;

    /**
     * Output message to command line
     *
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (!$this->output) {
            return;
        }

        /* @var \DateTime $datetime */
        $datetime = $record['datetime'];
        $time     = $datetime->format('Y/m/d-H:i:s');

        // Record message
        $level     = $record['level'];
        $message   = $record['message'];
        $levelName = $record['level_name'];
        $levelName = \sprintf('[%s]', $levelName);

        // Add level tag
        $tagName   = self::STYLES[$level] ?? 'info';
        $levelName = ColorTag::add($levelName, $tagName);

        // Format message
        $message = \sprintf('%s %s %s' . PHP_EOL, $time, $levelName, $message);

        echo Color::render($message);
    }

    /**
     * @param array $levels
     */
    public function setLevels(array $levels): void
    {
        $this->levels = $levels;
    }

    /**
     * @param FormatterInterface $formatter
     */
    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * @param bool $output
     */
    public function setOutput(bool $output): void
    {
        $this->output = $output;
    }
}