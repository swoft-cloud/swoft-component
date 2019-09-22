<?php
/**
 * Internal develop tool
 */

use SwoftTool\Command\DeleteRemoteTag;
use SwoftTool\Command\GenReadme;
use SwoftTool\Command\GenVersion;
use SwoftTool\Command\GitFindTag;
use SwoftTool\Command\GitAddRemote;
use SwoftTool\Command\GitForcePush;
use SwoftTool\Command\GitReleaseTag;
use SwoftTool\Command\GitSubtreePush;
use SwoftTool\Command\GitSubtreePull;
use SwoftTool\Command\UpdateSwooleVer;
use Toolkit\Cli\App;

require __DIR__ . '/script/bootstrap.php';

define('BASE_PATH', __DIR__);

$cli = new App();
$cli->addByConfig($gi = new GitFindTag(), $gi->getHelpConfig());
$cli->addByConfig($drt = new DeleteRemoteTag(), $drt->getHelpConfig());
$cli->addByConfig($grt = new GitReleaseTag(), $grt->getHelpConfig());
$cli->addByConfig($gar = new GitAddRemote(), $gar->getHelpConfig());
$cli->addByConfig($gfp = new GitForcePush(), $gfp->getHelpConfig());
$cli->addByConfig($gsp1 = new GitSubtreePush(), $gsp1->getHelpConfig());
$cli->addByConfig($gsp2 = new GitSubtreePull(), $gsp2->getHelpConfig());

$cli->addByConfig($cmd = new GenReadme(), $cmd->getHelpConfig());
$cli->addByConfig($cmd = new GenVersion(), $cmd->getHelpConfig());
$cli->addByConfig($cmd = new UpdateSwooleVer(), $cmd->getHelpConfig());

$cli->run();
