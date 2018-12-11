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
namespace Swoft\Rpc\Packer;

/**
 * Packer Interface
 */
interface PackerInterface
{
    /**
     * Pack data
     *
     * @param mixed $data
     * @return mixed
     */
    public function pack($data);

    /**
     * Unpack data
     *
     * @param mixed $data
     * @return mixed
     */
    public function unpack($data);
}
