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
 * Interface OutputInterface
 * @since 1.0
 */
interface OutputInterface
{
    /**
     * Write a message to standard output stream.
     * @param mixed       $messages Output message
     * @param bool        $nl true 会添加换行符 false 原样输出，不添加换行符
     * @param int|boolean $quit If is int, setting it is exit code.
     * @param array       $opts
     * @return int
     */
    public function write($messages, $nl = true, $quit = false, array $opts = []): int;
}
