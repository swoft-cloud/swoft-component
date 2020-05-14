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

/**
 * Class SimpleTextBar
 */
class SimpleTextBar extends NotifyMessage
{
    /**
     * Render a simple text progress bar by 'yield'
     *
     * @param int    $total
     * @param string $waitMsg
     * @param string $doneMsg
     *
     * @return Generator
     */
    public static function gen(int $total, string $waitMsg, string $doneMsg = ''): Generator
    {
        $current  = 0;
        $finished = false;
        $tpl      = (Cli::isSupportColor() ? "\x0D\x1B[2K" : "\x0D\r") . "%' 3d%% %s";
        $waitMsg  = Console::style()->render($waitMsg);
        $doneMsg  = $doneMsg ? Console::style()->render($doneMsg) : '';

        while (true) {
            if ($finished) {
                break;
            }

            $step = yield;

            if ((int)$step <= 0) {
                $step = 1;
            }

            $current += $step;
            $percent = ceil(($current / $total) * 100);

            if ($percent >= 100) {
                $percent  = 100;
                $finished = true;
                $waitMsg  = $doneMsg ?: $waitMsg;
            }

            // printf("\r%d%% %s", $percent, $msg);
            // printf("\x0D\x2K %d%% %s", $percent, $msg);
            // printf("\x0D\r%'2d%% %s", $percent, $msg);
            printf($tpl, $percent, $waitMsg);

            if ($finished) {
                echo "\n";
                break;
            }
        }

        yield false;
    }
}
