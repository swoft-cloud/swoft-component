<?php

namespace Swoft\Session\Handler;

use Swoft\Http\Message\Server\Request;

/**
 * @uses      NeedRequestInterface
 * @version   2018年02月01日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2018 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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