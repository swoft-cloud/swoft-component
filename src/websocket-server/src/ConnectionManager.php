<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Concern\AbstractSessionManager;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class ConnectionManager
 *
 * @since 2.0.8
 * @Bean(WsServerBean::MANAGER)
 */
class ConnectionManager extends AbstractSessionManager
{
    /**
     * @param string $sessionData
     *
     * @return SessionInterface
     */
    protected function restoreSession(string $sessionData): SessionInterface
    {
        /** @var SessionInterface $class */
        $data  = JsonHelper::decode($sessionData, true);

        return Connection::newFromArray($data);
    }
}
