<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\ErrorHandler;

/**
 * Interface HandlerInterface
 *
 * @package Swoft\ErrorHandler
 */
interface HandlerInterface
{
    /**
     * @param \Throwable $throwable
     * @return mixed
     */
    public function handle(\Throwable $throwable);
}
