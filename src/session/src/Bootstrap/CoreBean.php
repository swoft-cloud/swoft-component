<?php

namespace Swoft\Session\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Session\SessionManager;

/**
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
            'sessionManager' => [
                'class' => SessionManager::class,
                'config' => [
                    'driver' => 'file',
                    'lifetime' => 120,
                    'expire_on_close' => false,
                    'encrypt' => false,
                    'storage' => '@runtime/sessions',
                    'name' => 'SWOFT_SESSION_ID',
                ],
            ],
        ];
    }

}
