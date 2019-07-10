<?php
/**
 * Internal develop tool
 */

use SwoftTool\Command\DeleteRemoteTag;
use SwoftTool\Command\GenReadme;
use SwoftTool\Command\GenVersion;
use SwoftTool\Command\GitFindTag;
use SwoftTool\Command\GitAddRemote;
use SwoftTool\Command\GitReleaseTag;
use SwoftTool\Command\GitSubtreePush;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();

$cli->addByConfig($gi = new GitFindTag(), $gi->getHelpConfig());
$cli->addByConfig($drt = new DeleteRemoteTag(), $drt->getHelpConfig());
$cli->addByConfig($gar = new GitAddRemote(), $gar->getHelpConfig());
$cli->addByConfig($gsp = new GitSubtreePush(), $gsp->getHelpConfig());
$cli->addByConfig($grt = new GitReleaseTag(), $grt->getHelpConfig());

$cli->addCommand('gen:readme', $gr = new GenReadme(), $gr->getHelpConfig());
$cli->addCommand('gen:version', $gv = new GenVersion(), $gv->getHelpConfig());

$cli->run();
