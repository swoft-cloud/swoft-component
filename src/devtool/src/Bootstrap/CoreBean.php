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

namespace Swoft\Devtool\Bootstrap;

use Swoft\App;
use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;

/**
 * The core bean
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    /**
     * CoreBean constructor.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        App::setAlias('@devtool', \dirname(__DIR__, 2));
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [];
    }
}
