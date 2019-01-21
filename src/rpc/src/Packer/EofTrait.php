<?php
namespace Swoft\Rpc\Packer;

use Swoft\App;

/**
 * Trait EofTrait
 *
 * @package Swoft\Rpc\Packer
 */
trait EofTrait
{

    /**
     * @return string
     */
    public function getEof(): string
    {
        $properties = App::getAppProperties();
        return $properties->get('server.tcp.package_eof', '');
    }
}
