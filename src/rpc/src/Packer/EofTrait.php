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
