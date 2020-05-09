<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Contract;

/**
 * Interface FormatterInterface
 * @package Swoft\Console\Advanced
 */
interface FormatterInterface
{
    public const FINISHED = -1;

    public const CHAR_SPACE     = ' ';

    public const CHAR_HYPHEN    = '-';

    public const CHAR_UNDERLINE = '_';

    public const CHAR_VERTICAL  = '|';

    public const CHAR_EQUAL     = '=';

    public const CHAR_STAR      = '*';

    public const POS_LEFT   = 'l';

    public const POS_MIDDLE = 'm';

    public const POS_RIGHT  = 'r';

    public function format(): string;
}
