<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
require_once __DIR__ . '/bootstrap.php';

use Swoft\Rpc\Server\Command\RpcCommand;

$command = bean(RpcCommand::class);

$dir = alias('@runtime/logs');
@mkdir($dir, 0777, true);

$command->restart();
