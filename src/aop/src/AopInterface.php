<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop;

/**
 * AopInterface
 */
interface AopInterface
{
    /**
     * register aop
     *
     * @param array $aspects
     */
    public function register(array $aspects);
}
