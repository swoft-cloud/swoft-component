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

namespace Swoft\Session\Handler;

use Swoft\Http\Message\Server\Request;

/**
 * Interface NeedRequestInterface
 *
 * @package Swoft\Session\Handler
 */
interface NeedRequestInterface
{
    /**
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * @param Request $request
     * @return static
     */
    public function setRequest(Request $request);
}
