<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Advanced\Formatter;

use Swoft\Console\Advanced\MessageFormatter;
use Swoft\Console\Console;
use Swoft\Console\Helper\FormatUtil;
use Swoft\Stdlib\Helper\Str;
use Toolkit\Cli\Cli;
use function array_merge;
use function is_array;
use function is_scalar;

/**
 * Class Tree
 * @package Swoft\Console\Advanced\Formatter
 */
class Tree extends MessageFormatter
{
    /** @var int */
    private $counter = 0;

    /** @var bool */
    private $started = false;

    /**
     * Render data like tree
     * ├ ─ ─
     * └ ─
     * @param array $data
     * @param array $opts
     */
    public static function show(array $data, array $opts = []): void
    {
        static $counter = 0;
        static $started = 1;

        if ($started) {
            $started = 0;
            $opts    = array_merge([
                // 'char' => Cli::isSupportColor() ? '─' : '-', // ——
                'char'        => '-',
                'prefix'      => Cli::isSupportColor() ? '├' : '|',
                'leftPadding' => '',
            ], $opts);

            $opts['_level']   = 1;
            $opts['_is_main'] = true;

            Console::startBuffer();
        }

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $counter++;
                $leftString = $opts['leftPadding'] . Str::pad($opts['prefix'], $opts['_level'] + 1, $opts['char']);

                Console::write($leftString . ' ' . FormatUtil::typeToString($value));
            } elseif (is_array($value)) {
                $newOpts             = $opts;
                $newOpts['_is_main'] = false;
                $newOpts['_level']++;

                self::show($value, $newOpts);
            }
        }

        if ($opts['_is_main']) {
            Console::write('node count: ' . $counter);
            Console::flushBuffer();

            // reset.
            $counter = $started = 0;
        }
    }
}
