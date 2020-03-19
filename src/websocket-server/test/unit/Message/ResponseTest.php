<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\Message;

use PHPUnit\Framework\TestCase;
use Swoft\WebSocket\Server\Message\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    public function testBasic(): void
    {
        $w = Response::new(22);

        $this->assertSame(22, $w->getFd());
        $this->assertSame(22, $w->getSender());
        $this->assertSame(1, $w->getFinish());

        $w
            ->setFd(33)
            ->setSender(33)
            ->setOpcode(7)
            ->setFinish(false);

        $this->assertSame(33, $w->getFd());
        $this->assertSame(33, $w->getSender());
        $this->assertSame(7, $w->getOpcode());
        $this->assertFalse($w->isSent());
        $this->assertFalse($w->isFinish());
        $this->assertSame(0, $w->getFinish());

        $this->assertFalse($w->isSendToAll());
        $w->toAll();
        $this->assertTrue($w->isSendToAll());

        $this->assertEmpty($w->getFds());
        $w->toSome([23, 34]);
        $this->assertSame([23, 34], $w->getFds());

        $w->setFds([]);
        $this->assertEmpty($w->getFds());
        $w->toMore([33, 34]);
        $this->assertSame([33, 34], $w->getFds());

        $this->assertTrue($w->isEmpty());
        $w->setContent('hi');
        $this->assertFalse($w->isEmpty());
        $w->setContent('')->setExt(['a']);
        $this->assertFalse($w->isEmpty());
        $w->setContent('')->setExt([])->setData(['a']);
        $this->assertFalse($w->isEmpty());
    }
}
