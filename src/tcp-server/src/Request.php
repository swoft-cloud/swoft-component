<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Concern\DataPropertyTrait;
use Swoft\Tcp\Package;
use Swoft\Tcp\Server\Contract\RequestInterface;

/**
 * Class Request
 *
 * @since 2.0.4
 * @Bean(name=TcpServerBean::REQUEST, scope=Bean::PROTOTYPE)
 */
class Request implements RequestInterface
{
    use DataPropertyTrait;

    /**
     * The request data key for storage matched route info.
     * eg:
     * [
     *  status,
     *  [
     *      command => string,
     *      handler => [class, method],
     *  ]
     * ]
     */
    public const ROUTE_INFO = '__route';

    /**
     * Receiver fd
     *
     * @var int
     */
    private $fd = -1;

    /**
     * Received raw data
     *
     * @var string
     */
    private $rawData = '';

    /**
     * @var int
     */
    private $reactorId = -1;

    /**
     * Request package instance.
     * Notice: Available only on enable internal route dispatching
     *
     * @var Package
     */
    private $package;

    /**
     * @param int    $fd
     * @param string $data
     * @param int    $reactorId
     *
     * @return self
     */
    public static function new(int $fd, string $data, int $reactorId): self
    {
        /** @var self $self */
        $self = Swoft::getBean(TcpServerBean::REQUEST);

        // Set properties
        $self->fd        = $fd;
        $self->rawData   = $data;
        $self->reactorId = $reactorId;

        return $self;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->reactorId;
    }

    /**
     * Get received raw data
     *
     * @return string
     */
    public function getRawData(): string
    {
        return $this->rawData;
    }

    /**
     * Get request package instance.
     * Notice: Available only on enable internal route dispatching
     *
     * @return Package
     */
    public function getPackage(): Package
    {
        return $this->package;
    }

    /**
     * @param Package $package
     */
    public function setPackage(Package $package): void
    {
        $this->package = $package;
    }
}
