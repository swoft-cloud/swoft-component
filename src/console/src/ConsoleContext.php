<?php declare(strict_types=1);

namespace Swoft\Console;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Context\AbstractContext;

/**
 * Class ConsoleContext
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class ConsoleContext extends AbstractContext
{
    /**
     * @return Input
     */
    public function getInput(): Input
    {
        return \Swoft::getSingleton('input');
    }

    /**
     * @return Input
     */
    public function getOutput(): Output
    {
        return \Swoft::getSingleton('output');
    }
}
