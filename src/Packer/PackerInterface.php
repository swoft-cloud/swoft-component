<?php

namespace Swoft\Rpc\Packer;

/**
 * Packer Interface
 */
interface PackerInterface
{
    /**
     * pack data
     *
     * @param mixed $data
     * @return mixed
     */
    public function pack($data);

    /**
     * unpack data
     *
     * @param mixed $data
     * @return mixed
     */
    public function unpack($data);
}
