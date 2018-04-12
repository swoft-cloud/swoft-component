<?php

namespace Swoft\Rpc\Packer;

use Swoft\App;
use Swoft\Core\RequestContext;
use Swoft\Helper\JsonHelper;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Packer\Json\JsonPacker;

/**
 * RPC Service data packer
 */
class ServicePacker implements PackerInterface
{
    /**
     * @var string
     */
    private $type = 'json';

    /**
     * @var array
     */
    private $packers = [];

    /**
     * @param mixed $data
     * @param string $type
     *
     * @return mixed
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function pack($data, string $type = "")
    {
        $packer = $this->getPacker($type);

        return $packer->pack($data);
    }

    /**
     * @param mixed $data
     * @param string $type
     *
     * @return mixed
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function unpack($data, string $type = "")
    {
        $packer = $this->getPacker($type);

        return $packer->unpack($data);
    }

    /**
     * Get packer from config
     *
     * @param string $type
     *
     * @return PackerInterface
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function getPacker(string $type = ""): PackerInterface
    {
        $packers = $this->mergePackers();
        if (!isset($packers[$this->type])) {
            throw new RpcException(sprintf('the %s of packer in not exist', $this->type));
        }
        $packerName = $packers[$this->type];
        $packer     = App::getBean($packerName);
        if (!($packer instanceof PackerInterface)) {
            throw new RpcException(sprintf('the %s of packer in not instance of PackerInterface', $this->type));
        }

        return $packer;
    }

    /**
     * Format the data for packer
     *
     * @param string $interface
     * @param string $version
     * @param string $method
     * @param array  $params
     *
     * @return array
     */
    public function formatData(string $interface, string $version, string $method, array $params): array
    {
        $logid  = RequestContext::getLogid();
        $spanid = RequestContext::getSpanid() + 1;

        $data = [
            'interface' => $interface,
            'version'   => $version,
            'method'    => $method,
            'params'    => $params,
            'logid'     => $logid,
            'spanid'    => $spanid,
        ];

        return $data;
    }

    /**
     * validate the data of packer
     *
     * @param array $data params
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function checkData(array $data)
    {
        // check formatter
        if (!isset($data['status']) || !isset($data['data']) || !isset($data['msg'])) {
            throw new RpcException('the return of rpc is incorrected，data=' . JsonHelper::encode($data, JSON_UNESCAPED_UNICODE));
        }

        // check status
        $status = $data['status'];
        if ($status !== 200) {
            throw new RpcException('the return status of rpc is incorrected，data=' . JsonHelper::encode($data, JSON_UNESCAPED_UNICODE));
        }

        return $data['data'];
    }

    /**
     * Merge default and config packers
     *
     * @return array
     */
    public function mergePackers(): array
    {
        return array_merge($this->packers, $this->defaultPackers());
    }

    /**
     * Default packers
     *
     * @return array
     */
    public function defaultPackers(): array
    {
        return [
            'json' => JsonPacker::class,
        ];
    }
}
