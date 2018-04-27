<?php

namespace Swoft\Session\Handler;

use Swoft\Http\Message\Server\Request;

/**
 * Class NeedRequestInterface
 * @author    huangzhhui <huangzhwork@gmail.com>
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
