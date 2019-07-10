<?php
/**
 * Internal develop tool
 */

use SwoftTool\Command\DeleteRemoteTag;
use SwoftTool\Command\GenReadme;
use SwoftTool\Command\GenVersion;
use SwoftTool\Command\FindGitTag;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addByConfig($gi = new FindGitTag(), $gi->getHelpConfig());
$cli->addByConfig($drt = new DeleteRemoteTag(), $drt->getHelpConfig());

$cli->addCommand('gen:readme', $gr = new GenReadme(), $gr->getHelpConfig());
$cli->addCommand('gen:version', $gv = new GenVersion(), $gv->getHelpConfig());

$cli->run();
