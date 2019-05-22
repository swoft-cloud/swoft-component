<?php declare(strict_types=1);

namespace Swoft\Console\Advanced\Interact;

use function stripos;
use Swoft\Console\Advanced\InteractMessage;
use Swoft\Console\Console;
use Swoft\Console\Helper\Show;
use function trim;
use function ucfirst;

/**
 * Class Confirm
 * @package Swoft\Console\Advanced\Interact
 */
class Confirm extends InteractMessage
{
    /**
     * Send a message request confirmation
     * @param string $question The question message
     * @param bool   $default Default value
     * @return bool
     */
    public static function ask(string $question, bool $default = true): bool
    {
        if (!$question = trim($question)) {
            Show::warning('Please provide a question message!', 1);
            return false;
        }

        $defText  = $default ? 'yes' : 'no';
        $question = ucfirst(trim($question, '?'));
        $message  = "<comment>$question ?</comment>\nPlease confirm (yes|no)[default:<info>$defText</info>]: ";

        while (true) {
            $answer = Console::readChar($message);

            if ('' === $answer) {
                return $default;
            }

            if (0 === stripos($answer, 'y')) {
                return true;
            }

            if (0 === stripos($answer, 'n')) {
                return false;
            }
        }

        return false;
    }
}
