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
 * the join point of interface
 *
 * @uses      JoinPointInterface
 * @version   2017年12月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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
    public function getMethod();
}
