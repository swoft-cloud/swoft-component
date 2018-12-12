<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Event;

class TaskEvent
{
    const BEFORE_TASK = 'beforeTask';

    const AFTER_TASK = 'afterTask';

    const FINISH_TASK = 'finish';
}
