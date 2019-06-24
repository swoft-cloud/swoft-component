<?php
/**
 * Internal develop tool
 */

use SwoftTool\Command\GenReadme;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addCommand('gen:readme', new GenReadme(), 'generate readme file for an component');
$cli->run();
