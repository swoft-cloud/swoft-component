<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Sets;

use Swoft\Redis\Operator\Command;

class SetIsMember extends Command
{
    /**
     * [Set] sContains - sIsMember
     *
     * @return string
     */
    public function getId()
    {
        return 'sContains';
    }

    /**
     * @param string $data
     * @return bool
     */
    public function parseResponse($data)
    {
        return (bool)$data;
    }
}
