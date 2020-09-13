<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use Swoft\Stdlib\Helper\Arr;
use Swoole\Server as SwooleServer;

/**
 * Class ServiceServer
 *
 * @since 2.0
 *
 * @Bean(name="rpcServer")
 */
class ServiceServer extends Server
{
    /**
     * @var string
     */
    protected static $serverType = 'RPC';

    /**
     * Default port
     *
     * @var int
     */
    protected $port = 18307;

    /**
     * @var string
     */
    protected $pidName = 'swoft-rpc';

    /**
     * @var string
     */
    protected $commandFile = '@runtime/swoft-rpc.command';

    /**
     * @var string
     */
    protected $pidFile = '@runtime/swoft-rpc.pid';

    /**
     * Start server
     *
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new SwooleServer($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }

    /**
     * @return array
     */
    public function defaultSetting(): array
    {
        $setting = [
            'open_eof_check' => true,
            'package_eof'    => "\r\n\r\n",
        ];

        return Arr::merge(parent::defaultSetting(), $setting);
    }
}
