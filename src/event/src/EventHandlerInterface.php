<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Event;

/**
 * Interface EventHandlerInterface - 独立的事件监听器接口
 * @package Swoft\Event
 * @author    inhere <in.798@qq.com>
 */
interface EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void;
}
