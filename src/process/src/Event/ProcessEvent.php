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

namespace Swoft\Process\Event;

/**
 * The process event
 */
class ProcessEvent
{
    /**
     * Before process
     */
    const BEFORE_PROCESS = 'beforeProcess';

    /**
     * After process
     */
    const AFTER_PROCESS = 'afterProcess';
}
