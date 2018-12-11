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

namespace SwoftTest\Session\Cases;

use Swoft\App;
use Swoft\Session\Middleware\SessionMiddleware;
use Swoft\Session\SessionManager;
use Swoft\Session\SessionStore;

class SessionTest extends AbstractTestCase
{
    public function getSession()
    {
        /** @var SessionManager $manager */
        $manager = bean('sessionManager');

        $name = SessionMiddleware::DEFAULT_SESSION_NAME;
        $id = uniqid();
        $handler = $manager->createHandlerByConfig();
        return new SessionStore($name, $handler, $id);
    }

    public function testSetGetSession()
    {
        /** @var SessionManager $manager */
        $manager = bean('sessionManager');

        $session = $this->getSession();
        $manager->setSession($session);
        $this->assertEquals($session, $manager->getSession());

        if (App::isCoContext()) {
            $session = $this->getSession();
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());

            $session = $this->getSession();
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());
        }
    }
}
