<?php

namespace Swoft\InternalDev\Command;

use Swoft\App;
use Swoft\Console\Bean\Annotation\Mapping;
use Swoft\Console\Output\Output;
use Swoft\Devtool\PharCompiler;
use Swoft\Helper\DirHelper;
use Swoft\Console\Bean\Annotation\Command;

/**
 * Class ComponentCommand
 * @package Swoft\InternalDev\Command
 * @author inhere
 * @Command("cdev")
 */
class ComponentCommand
{
    const TYPE_SSL = 'git@github.com:';
    const TYPE_HTTPS = 'https://github.com/';

    /**
     * @var string
     * https eg. https://github.com/swoft-cloud/swoft-devtool.git
     * ssl eg. git@github.com:swoft-cloud/swoft-devtool.git
     */
    public $baseUrl = 'https://github.com/swoft-cloud/swoft-%s.git';

    /**
     * @Mapping()
     */
    public function test()
    {
        echo 'hel';
    }
}
