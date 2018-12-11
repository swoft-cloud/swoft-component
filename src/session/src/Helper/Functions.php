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
if (! function_exists('session')) {
    /**
     * Get Session
     *
     * @return \Swoft\Session\SessionInterface
     */
    function session()
    {
        /** @var \Swoft\Session\SessionManager $sessionManager */
        $sessionManager = \Swoft\App::getBean('sessionManager');
        return $sessionManager->getSession();
    }
}
