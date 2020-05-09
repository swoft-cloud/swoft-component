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
 * Interface CommandHandlerInterface
 * - Use for implement a independent command handler
 * @since 2.0
 */
interface CommandHandlerInterface
{
    /**
     * @return mixed
     */
    public function execute();
}
