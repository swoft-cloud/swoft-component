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
namespace Swoft\Rpc\Server\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Rpc\Server\Router\HandlerMapping;
use Swoft\Rpc\Server\ServiceDispatcher;

/**
 * The core bean of service
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'ServiceDispatcher' => [
                'class' => ServiceDispatcher::class,
            ],
            'serviceRouter'     => [
                'class' => HandlerMapping::class,
            ],
        ];
    }
}
