<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Advanced\Progress;

use Generator;
use Swoft\Console\Advanced\NotifyMessage;
use Swoft\Console\Console;
use Toolkit\Cli\Cli;
use function array_merge;
use function ceil;

/**
 * Class SimpleBar
 */
class SimpleBar extends NotifyMessage
{
    /**
     * Render a simple progress bar by 'yield'
     *
     * ```php
     * $i = 0;
     * $total = 120;
     * $bar = Show::progressBar($total, [
     *     'msg' => 'Msg Text',
     *     'doneChar' => '#'
     * ]);
     * echo "progress:\n";
     * while ($i <= $total) {
     *      $bar->send(1); // 发送步进长度，通常是 1
     *      usleep(50000);
     *      $i++;
     * }
     * ```
     *
     * @param int   $total
     * @param array $opts
     * @internal int $current
     * @return Generator
     */
    public static function gen(int $total, array $opts = []): Generator
    {
        $current   = 0;
        $finished  = false;
        $tplPrefix = Cli::isSupportColor() ? "\x0D\x1B[2K" : "\x0D\r";

        $opts = array_merge([
            'doneChar' => '=',
            'waitChar' => ' ',
            'signChar' => '>',
            'msg'      => '',
            'doneMsg'  => '',
        ], $opts);

        $waitMsg  = Console::style()->render($opts['msg']);
        $doneMsg  = Console::style()->render($opts['doneMsg']);
        $waitChar = $opts['waitChar'];

        while (true) {
            if ($finished) {
                break;
            }

            $step = yield;

            if ((int)$step <= 0) {
                $step = 1;
            }

            $current += $step;
            $percent = (int)ceil(($current / $total) * 100);

            if ($percent >= 100) {
                $waitMsg  = $doneMsg ?: $waitMsg;
                $percent  = 100;
                $finished = true;
            }

            /**
             * \r, \x0D 回车，到行首
             * \x1B ESC
             * 2K 清除本行
             */
            // printf("\r[%'--100s] %d%% %s",
            // printf("\x0D\x1B[2K[%'{$waitChar}-100s] %d%% %s",
            printf(
                "{$tplPrefix}[%'{$waitChar}-100s] %' 3d%% %s",
                str_repeat($opts['doneChar'], $percent) . ($finished ? '' : $opts['signChar']),
                $percent,
                $waitMsg
            );// ♥ ■ ☺ ☻ = #

            if ($finished) {
                echo "\n";
                break;
            }
        }

        yield false;
    }
}
