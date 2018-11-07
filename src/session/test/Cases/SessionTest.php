<?php

namespace SwoftTest\Session;

use Swoft\Session\Middleware\SessionMiddleware;
use Swoft\Session\SessionInterface;
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

        go(function () use ($manager) {
            $session = $this->getSession();
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());
        });

        go(function () use ($manager) {
            $session = $this->getSession();
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());
        });
    }
}