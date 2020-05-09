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

/**
 * Class InputDefinition
 *
 * @since 2.0 refer inhere/console and symfony/console
 */
class InputDefinition
{
    /**
     * @return InputDefinition
     */
    public static function create(): self
    {
        return new self();
    }
}
