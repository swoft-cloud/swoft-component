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
namespace Swoft\Redis\Operator\Sets;

use Swoft\Redis\Operator\Command;

class SetCardinality extends Command
{
    /**
     * [Set] sSize - sCard
     *
     * @return string
     */
    public function getId()
    {
        return 'sSize';
    }
}
