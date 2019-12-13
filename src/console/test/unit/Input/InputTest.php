<?php declare(strict_types=1);

namespace SwoftTest\Console\Unit\Input;

use PHPUnit\Framework\TestCase;
use Swoft\Console\Exception\CommandFlagException;
use Swoft\Console\Input\Input;

class InputTest extends TestCase
{
    /**
     * @throws CommandFlagException
     */
    public function testBindingFlags(): void
    {
        $args = ['bin/swoft', '-d', '12'];
        // value sync binding
        $info = [
            'options'   => [
                'day' => [
                    'short' => 'd',
                ],
            ],
            'arguments' => [],
        ];

        $in = new Input($args);
        $in->bindingFlags($info);

        $this->assertSame('12', $in->getOpt('day'));
        $this->assertSame('12', $in->getOpt('d'));

        $args = ['bin/swoft', '--day', '12'];
        $in = new Input($args);
        $in->bindingFlags($info);

        $this->assertSame('12', $in->getOpt('day'));
        $this->assertSame('12', $in->getOpt('d'));
    }
}
