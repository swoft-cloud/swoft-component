<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Redis\Redis;

/**
 * CoreBean
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    public function beans()
    {
        return [
            Redis::class => [
                'class' => Redis::class
            ]
        ];
    }
}
