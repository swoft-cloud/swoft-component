<?php

require_once dirname(__FILE__, 2) . '/vendor/autoload.php';
require_once dirname(__FILE__, 2) . '/test/config/define.php';

// init
\Swoft\Bean\BeanFactory::init();

\Swoft\App::$isInTest = true;

/* @var \Swoft\Bootstrap\Boots\Bootable $bootstrap */
$bootstrap = \Swoft\App::getBean(\Swoft\Bootstrap\Bootstrap::class);
$bootstrap->bootstrap();

use Swoft\Http\Server\Command\ServerCommand;
use Swoft\WebSocket\Server\Command\WsCommand;

$command = bean(WsCommand::class);

$dir = alias('@runtime/logs');
@mkdir($dir, 0777, true);

$command->restart();