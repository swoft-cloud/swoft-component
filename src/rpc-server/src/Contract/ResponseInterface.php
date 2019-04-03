<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Contract;


use Swoft\Rpc\Error;
use Swoole\Server;

/**
 * Class ResponseInterface
 *
 * @since 2.0
 */
interface ResponseInterface
{

    /**
     * @param Error $error
     *
     * @return ResponseInterface
     */
    public function withError(Error $error): ResponseInterface;

    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function withData($data): ResponseInterface;

    /**
     * @param string $content
     *
     * @return ResponseInterface
     */
    public function withContent(string $content): ResponseInterface;

    /**
     * @return bool
     */
    public function send(): bool;

    /**
     * @return Server
     */
    public function getServer(): Server;

    /**
     * @return int
     */
    public function getFd(): int;

    /**
     * @return int
     */
    public function getReactorId(): int;
}