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
     * @return ConsoleContext
     * @throws \Throwable
     */
    public static function new(): self
    {
        /** @var self $ctx */
        $ctx = \Swoft::getPrototype(__CLASS__);
        $ctx->setMulti([
            'parentid' => '',
            'spanid'   => \uniqid('', 0),
            'traceid'  => \uniqid('', 0),
        ]);

        return $ctx;
    }

    /**
     * @return Input
     * @throws \Throwable
     */
    public function getInput(): Input
    {
        return \Swoft::getSingleton('input');
    }

    /**
     * @return Output
     * @throws \Throwable
     */
    public function getOutput(): Output
    {
        return \Swoft::getSingleton('output');
    }
}
