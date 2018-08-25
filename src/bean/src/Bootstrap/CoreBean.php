<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Bootstrap;

use Swoft\Bean\BootBean;
use Swoft\Core\BootBeanInterface;

/**
 * The corebean of swoft
 *
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    public function beans()
    {
        return [
        ];
    }
}
