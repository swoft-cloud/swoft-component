<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Console\Unit\Input;

use PHPUnit\Framework\TestCase;
use Swoft\Console\Exception\CommandFlagException;
use Swoft\Console\Input\Input;

class InputTest extends TestCase
{
    public function testInput(): void
    {
        $args = ['bin/swoft', 'input:test', '-d', '12'];
        $in = new Input($args);

        $this->assertSame('input:test', $in->getCommand());
        $this->assertSame([], $in->getArgs());
        $this->assertSame(['-d', '12'], $in->getFlags());
        $this->assertSame(['d' => '12'], $in->getOpts());
    }

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
                    'type'  => 'int',
                ],
            ],
            'arguments' => [],
        ];

        $in = new Input($args);
        $in->bindingFlags($info);

        $this->assertSame(12, $in->getOpt('day'));
        $this->assertSame(12, $in->getOpt('d'));

        $args = ['bin/swoft', '--day', '12'];
        $in = new Input($args);
        $in->bindingFlags($info);

        $this->assertSame(12, $in->getOpt('day'));
        $this->assertSame(12, $in->getOpt('d'));
    }
}
