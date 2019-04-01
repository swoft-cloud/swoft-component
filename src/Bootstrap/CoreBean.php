<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\ErrorHandler\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\ErrorHandler;

/**
 * The corebean of swoft
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            ErrorHandler\ErrorHandler::class => ['class' => ErrorHandler\ErrorHandler::class],
            ErrorHandler\ErrorHandlerChain::class => ['class' => ErrorHandler\ErrorHandlerChain::class],
        ];
    }
}
