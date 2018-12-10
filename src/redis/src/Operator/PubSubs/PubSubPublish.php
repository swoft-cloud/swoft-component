<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\PubSubs;

use Swoft\Redis\Operator\Command;

class PubSubPublish extends Command
{
    /**
     * [PubSub] publish
     *
     * @return string
     */
    public function getId()
    {
        return 'publish';
    }
}
