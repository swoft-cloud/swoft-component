<?php declare(strict_types=1);

namespace SwoftTool\Command;

use RuntimeException;
use Swoole\Coroutine;
use Swoole\Coroutine\Scheduler as CoScheduler;
use Swoole\Event;

/**
 * Class Scheduler
 *
 * @package SwoftTool\Command
 */
class Scheduler
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @param mixed ...$handlers
     *
     * @return Scheduler
     */
    public static function new(...$handlers): self
    {
        return new self(...$handlers);
    }

    /**
     * Class constructor.
     *
     * @param mixed ...$handlers
     */
    public function __construct(...$handlers)
    {
        if ($handlers) {
            foreach ($handlers as $callable) {
                $this->handlers[] = [$callable, []];
            }
        }
    }

    /**
     * @param callable $callable
     * @param mixed    ...$args
     */
    public function add(callable $callable, ...$args): void
    {
        $this->handlers[] = [$callable, $args];
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        if (!$this->handlers) {
            throw new RuntimeException('Not add any callable handler, cannot start');
        }

        // >= 4.4
        if ($this->isGteSwoole44()) {
            $scheduler = new CoScheduler;

            foreach ($this->handlers as [$callable, $args]) {
                $scheduler->add($callable, ...$args);
            }

            return $scheduler->start();
        }

        // < 4.4
        foreach ($this->handlers as [$callable, $args]) {
            Coroutine::create($callable, ...$args);
        }

        Event::wait();
        return true;
    }

    /**
     * Check swoole is >= 4.4.0
     *
     * @return bool
     */
    public function isGteSwoole44(): bool
    {
        return SWOOLE_VERSION_ID >= 40400;
    }
}
