<?php
 
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bootstrap\Listeners\Interfaces;

use Swoole\Server;

/**
 *
 *
 * @uses      BufferEmptyInterface
 * @version   2018年01月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface BufferEmptyInterface
{
    public function onBufferEmpty(Server $server, int $fd);
}
