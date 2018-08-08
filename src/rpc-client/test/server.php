<?php

require_once __DIR__ . '/bootstrap.php';

use Swoft\Rpc\Server\Command\RpcCommand;

$command = bean(RpcCommand::class);

$command->start();