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

namespace Swoft\Cache;

use Swoft\Core\AbstractResult;

/**
 * CacheDataResult
 */
class CacheDataResult extends AbstractResult
{
    /**
     * @param array ...$params
     *
     * @return mixed
     */
    public function getResult(...$params)
    {
        $this->release();
        return $this->result;
    }
}
