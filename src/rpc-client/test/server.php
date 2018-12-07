<?php

require_once __DIR__ . '/bootstrap.php';

use Swoft\Rpc\Server\Command\RpcCommand;

$command = bean(RpcCommand::class);

$dir = alias('@runtime/logs');
@mkdir($dir, 0777, true);

$command->restart();