<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashIncrByFloat extends Command
{
    /**
     * [Hash] hIncrByFloat
     *
     * @return string
     */
    public function getId()
    {
        return 'hIncrByFloat';
    }
}
