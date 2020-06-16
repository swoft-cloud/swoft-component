<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft;

use Swoft\Contract\WaitGroupInterface;
use Swoole\Coroutine\Channel;

class WaitGroup implements WaitGroupInterface
{
    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var int
     */
    private $count = 0;

    /**
     * WaitGroup constructor.
     */
    public function __construct()
    {
        $this->channel = new Channel();
    }

    /**
     * Add task
     */
    public function add(): void
    {
        $this->count++;
    }

    /**
     * Done task
     */
    public function done(): void
    {
        $this->channel->push(1);
    }

    /**
     * Wait task
     */
    public function wait(): void
    {
        while ($this->count--) {
            $this->channel->pop();
        }
    }
}
