<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Proxy\Contract;

/**
 * Class VisitorInterface
 *
 * @since 2.0
 */
interface VisitorInterface
{
    /**
     * @return string
     */
    public function getProxyName(): string;

    /**
     * @return string
     */
    public function getProxyClassName(): string;
}
