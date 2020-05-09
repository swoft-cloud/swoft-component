<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Event\Unit\Listener;

use PHPUnit\Framework\TestCase;
use Swoft\Event\Event;
use Swoft\Event\EventInterface;
use Swoft\Event\Listener\LazyListener;

/**
 * Class LazyListenerTest
 */
class LazyListenerTest extends TestCase
{
    public function testCall(): void
    {
        $listener = LazyListener::create(function (EventInterface $e) {
            /** @var Event $e */
            $this->assertSame('lazy', $e->getName());
            $e->setParam('word', 'ABC');
        });

        $this->assertNotEmpty($listener->getCallback());

        $e = Event::create('lazy');

        $listener->handle($e);

        $this->assertSame('ABC', $e->getParam('word'));
    }
}
