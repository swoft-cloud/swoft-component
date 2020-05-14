<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Console\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Console\Helper\DocBlock;

/**
 * Class DocBlockTest
 */
class DocBlockTest extends TestCase
{
    public function testGetTags(): void
    {
        $comment = <<<DOC
/**
 * Provide some commands to manage the HTTP Server
 *
 * @since 2.0
 *
 * @example
 *  {fullCmd}:start     Start the http server
 *  {fullCmd}:stop      Stop the http server
 */
DOC;
        $ret     = DocBlock::getTags($comment);
        $this->assertCount(3, $ret);
        $this->assertArrayHasKey('since', $ret);
        $this->assertArrayHasKey('example', $ret);
        $this->assertArrayHasKey('description', $ret);

        $ret = DocBlock::getTags($comment, ['allow' => ['example']]);
        $this->assertCount(2, $ret);
        $this->assertArrayNotHasKey('since', $ret);
        $this->assertArrayHasKey('example', $ret);
        $this->assertArrayHasKey('description', $ret);

        $ret = DocBlock::getTags($comment, [
            'allow'   => ['example'],
            'default' => 'desc'
        ]);
        $this->assertCount(2, $ret);
        $this->assertArrayNotHasKey('since', $ret);
        $this->assertArrayHasKey('example', $ret);
        $this->assertArrayHasKey('desc', $ret);
        $this->assertArrayNotHasKey('description', $ret);
    }
}
