<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Concern\AbstractSessionManager;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class ConnectionManager
 *
 * @since 2.0.8
 * @Bean("tcpConnectionManager")
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
