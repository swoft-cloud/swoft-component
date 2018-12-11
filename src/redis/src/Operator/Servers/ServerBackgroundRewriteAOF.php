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
namespace Swoft\Redis\Operator\Servers;

use Swoft\Redis\Operator\Command;

class ServerBackgroundRewriteAOF extends Command
{
    /**
     * [Server] bgrewriteaof
     *
     * @return string
     */
    public function getId()
    {
        return 'bgrewriteaof';
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($data)
    {
        return $data == 'Background append only file rewriting started';
    }
}
