<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Event\Listener;

use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Stdlib\Helper\PhpHelper;

/**
 * Class LazyListener - 将callable包装成对象
 * @package Swoft\Event\Listener
 * @since 2.0
 */
class LazyListener implements EventHandlerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     * @return LazyListener
     */
    public static function create(callable $callback): self
    {
        return new self($callback);
    }

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        return PhpHelper::call($this->callback, $event);
    }

    /**
     * @return callable|mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
