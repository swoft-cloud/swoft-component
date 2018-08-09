<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 16:56
 */

namespace Swoft\Encrypt\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;
use Swoft\Encrypt\Mapping\EncryptHandlerInterface;
use Swoft\Encrypt\SecretKey;
use Swoft\Exception\Exception;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Message\Stream\SwooleStream;
use Swoft\Http\Server\AttributeEnum;
use Swoft\Log\Log;

/**
 * @Bean()
 * Class EncryptHandler
 * @package Swoft\Encrypt\Handler
 */
class EncryptHandler implements EncryptHandlerInterface
{
    /**
     * @Value(name="${config.encrypt.padding}", env="${ENCRYPT_PADDING}")
     * @var int
     */
    private $padding = OPENSSL_PKCS1_PADDING;

    /**
     * @Inject()
     * @var SecretKey
     * 因底层bug, 应注入SecretKeyInterface
     */
    private $secretKey;

    public function encrypt($data): string
    {
        openssl_public_encrypt(
            json_encode($data),
            $encryptData,
            $this->secretKey->getPublicKey(),
            $this->padding
        );
        Log::debug($data);
        return base64_encode($encryptData);
    }

    /**
     * @param string $encryptData
     * @return mixed
     * @throws Exception
     */
    public function decrypt(string $encryptData)
    {
        openssl_private_decrypt(base64_decode($encryptData), $decryptData, $this->secretKey->getPrivateKey(), $this->padding);
        if (! $decryptData){
            throw new Exception("Decryption failure");
        }
        $parsedBody = json_decode($decryptData, true);
        Log::debug($parsedBody);
        return $parsedBody;
    }

    /**
     * @param $data
     * @return array
     */
    public function sign($data)
    {
        if (! is_array($data)){
            $data = [$data];
        }
        ksort($data);
        Log::debug($data);
        $body = urldecode(http_build_query($data));
        openssl_sign($body,$sign, $this->secretKey->getPrivateKey());
        return $data + ['sign' => base64_encode($sign)];
    }

    /**
     * @param string $raw
     * @return array
     * @throws Exception
     */
    public function verify(string $raw)
    {
        parse_str($raw, $parsedBody);
        Log::debug($parsedBody);
        if (! is_array($parsedBody) || empty($parsedBody['sign'])){
            throw new Exception("Verification failure");
        }

        $sign = $parsedBody['sign'];
        unset($parsedBody['sign']);
        ksort($parsedBody);
        $body = urldecode(http_build_query($parsedBody));

        if (! openssl_verify($body, base64_decode($sign), $this->secretKey->getPublicKey()) == 1){
            throw new Exception("Verification failure");
        }
        return $parsedBody;
    }
}