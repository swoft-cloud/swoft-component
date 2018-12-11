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

namespace Swoft\Session\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Session\SessionManager;

/**
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
            'sessionManager' => [
                'class' => SessionManager::class,
                'config' => [
                    'driver' => 'file',
                    'lifetime' => 1200,
                    'expire_on_close' => false,
                    'encrypt' => false,
                    'storage' => '@runtime/sessions',
                    'name' => 'SWOFT_SESSION_ID',
                ],
            ],
        ];
    }
}
