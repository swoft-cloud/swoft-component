<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Encrypt\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Encrypt\Handler\EncryptHandler;
use Swoft\Encrypt\Mapping\EncryptHandlerInterface;
use Swoft\Encrypt\Mapping\SecretKeyInterface;
use Swoft\Encrypt\SecretKey;

/**
 * Class CoreBean
 * @package Swoft\Encrypt\Bootstrap
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans()
    {
        return [
            SecretKeyInterface::class => [
                'class' => SecretKey::class
            ],
            EncryptHandlerInterface::class => [
                'class' => EncryptHandler::class
            ]
        ];
    }
}
