<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop\Contract;

/**
 * Class JoinPointInterface
 *
 * @since 2.0
 */
interface JoinPointInterface
{
    /**
     * @return array
     */
    public function getArgs(): array;

    /**
     * @return object
     */
    public function getTarget();

    /**
     * @return string
     */
    public function getMethod(): string;
}
