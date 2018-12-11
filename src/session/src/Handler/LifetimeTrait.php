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

trait LifetimeTrait
{
    /**
     * @var int
     */
    protected $lifetime = 0;

    /**
     * @param int $seconds
     * @return $this
     */
    public function setLifetime(int $seconds)
    {
        $this->lifetime = $seconds;
        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }
}
