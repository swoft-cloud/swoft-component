<?php
/**
 * Internal develop tool
 */

use SwoftTool\Command\GenReadme;
use SwoftTool\Command\GitInfo;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addCommand('gen:readme', $gr = new GenReadme(), $gr->getHelpConfig());
$cli->addCommand('git:tag', $gi = new GitInfo(), $gi->getHelpConfig());

$cli->run();
