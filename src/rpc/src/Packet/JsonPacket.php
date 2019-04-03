<?php declare(strict_types=1);


namespace Swoft\Rpc\Packet;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Protocol;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class JsonPacket
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class JsonPacket extends AbstractPacket
{
    /**
     * Json-rpc version
     */
    const VERSION = '2.0';

    /**
     * @param Protocol $protocol
     *
     * @return string
     */
    public function encode(Protocol $protocol): string
    {
        $version    = $protocol->getVersion();
        $interface  = $protocol->getInterface();
        $methodName = $protocol->getMethod();

        $method = sprintf('%s%s%s%s%s', $version, self::DELIMITER, $interface, self::DELIMITER, $methodName);
        $data   = [
            'jsonrpc' => self::VERSION,
            'method'  => $method,
            'params'  => $protocol->getParams(),
            'id'      => '',
            'ext'     => $protocol->getExt()
        ];

        $string = JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
        $string = $this->addPackageEof($string);
        return $string;
    }

    /**
     * @param string $string
     *
     * @return Protocol
     * @throws RpcException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function decode(string $string): Protocol
    {
        $data  = JsonHelper::decode($string, true);
        $error = json_last_error();
        if ($error != JSON_ERROR_NONE) {
            throw new RpcException(
                sprintf('Data(%s) is not json format!', $string)
            );
        }

        $method = $data['method'] ?? '';
        $params = $data['params'] ?? [];
        $ext    = $data['ext'] ?? [];

        if (empty($method)) {
            throw new RpcException(
                sprintf('Method(%s) cant not be empty!', $string)
            );
        }

        $methodAry = explode(self::DELIMITER, $method);
        if (count($methodAry) < 3) {
            throw new RpcException(
                sprintf('Method(%s) is bad format!', $method)
            );
        }

        [$version, $interfaceClass, $methodName] = $methodAry;

        if (empty($interfaceClass) || empty($methodName)) {
            throw new RpcException(
                sprintf('Interface(%s) or Method(%s) can not be empty!', $interfaceClass, $method)
            );
        }

        return Protocol::new($version, $interfaceClass, $methodName, $params, $ext);
    }
}