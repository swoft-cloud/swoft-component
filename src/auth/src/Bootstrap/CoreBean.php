<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Bootstrap;

use Swoft\Auth\AuthManager;
use Swoft\Auth\AuthUserService;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Auth\Mapping\AuthorizationParserInterface;
use Swoft\Auth\Mapping\AuthServiceInterface;
use Swoft\Auth\Parser\AuthorizationHeaderParser;
use Swoft\Auth\Parser\JWTTokenParser;
use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;

/**
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    public function beans(): array
    {
        return [
            AuthorizationParserInterface::class => [
                'class' => AuthorizationHeaderParser::class
            ],
            AuthManagerInterface::class => [
                'class' => AuthManager::class,
                'tokenParserClass' => JWTTokenParser::class,
            ],
            AuthServiceInterface::class => [
                'class' => AuthUserService::class
            ]
        ];
    }
}
