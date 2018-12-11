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

namespace Swoft\Session\Handler;

interface LifetimeInterface
{
    /**
     * @param int $seconds
     * @return $this
     */
    public function setLifetime(int $seconds);

    /**
     * @return int
     */
    public function getLifetime(): int;
}
