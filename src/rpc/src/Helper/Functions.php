<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
if (! function_exists('service_packer')) {
    /**
     * @return \Swoft\Rpc\Packer\ServicePacker
     */
    function service_packer()
    {
        return \Swoft\App::getBean('servicePacker');
    }
}
