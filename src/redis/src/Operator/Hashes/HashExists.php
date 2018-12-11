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
namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashExists extends Command
{
    /**
     * [Hash] hExists
     *
     * @return string
     */
    public function getId()
    {
        return 'hExists';
    }

    public function parseResponse($data)
    {
        return (bool)$data;
    }
}
