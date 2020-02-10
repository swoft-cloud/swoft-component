<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface ResponseInterface
 *
 * @since 2.0
 */
interface ResponseInterface
{
    /**
     * @return int
     */
    public function getFd(): int;

    /**
     * @return int
     */
    public function getSender(): int;

    /**
     * @param int $sender
     *
     * @return self
     */
    public function setSender(int $sender): self;

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data): self;
}
