<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/4/25
 * Time: 上午11:45
 */

namespace Swoft\WebSocket\Server;

/**
 * Interface CommandInterface
 * @package Swoft\WebSocket\Server
 */
interface CommandInterface
{
    /**
     * @return mixed
     */
    public function execute();
}
