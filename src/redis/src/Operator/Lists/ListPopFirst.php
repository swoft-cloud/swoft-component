<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Lists;

use Swoft\Redis\Operator\Command;

class ListPopFirst extends Command
{
    /**
     * [List] lPop
     *
     * @return string
     */
    public function getId()
    {
        return 'lPop';
    }
}
