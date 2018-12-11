<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Service;

/**
 * The interface of service connect
 */
interface ServiceConnectInterface
{
    public function reConnect();

    /**
     * @param string $data
     * @return bool
     */
    public function send(string $data): bool;

    /**
     * @return string
     */
    public function recv(): string;
}
