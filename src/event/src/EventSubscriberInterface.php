<?php declare(strict_types = 1);

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
 * Interface EventSubscriberInterface - 自定义配置多个相关的事件的监听器
 * @package Swoft\Event
 * @version   2017年08月30日
 * @author    inhere <in.798@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface EventSubscriberInterface
{
    /**
     * 配置事件与对应的处理方法(可以配置优先级)
     * @return array
     */
    public static function getSubscribedEvents(): array;
}
