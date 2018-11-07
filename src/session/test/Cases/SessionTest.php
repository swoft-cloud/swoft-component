<?php

namespace SwoftTest\Session;

use Swoft\Session\Middleware\SessionMiddleware;
use Swoft\Session\SessionInterface;
use Swoft\Session\SessionManager;
use Swoft\Session\SessionStore;

class SessionTest extends AbstractTestCase
{
    public function testSetGetSession()
    {
        /** @var SessionManager $manager */
        $manager = bean('sessionManager');

        $name = SessionMiddleware::DEFAULT_SESSION_NAME;
        $id = uniqid();
        $handler = $manager->createHandlerByConfig();
        $session = new SessionStore($name, $handler, $id);
        $manager->setSession($session);
        $this->assertEquals($session, $manager->getSession());

        go(function () use ($manager) {
            $name = SessionMiddleware::DEFAULT_SESSION_NAME;
            $id = uniqid();
            $handler = $manager->createHandlerByConfig();
            $session = new SessionStore($name, $handler, $id);
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());
        });

        go(function () use ($manager) {
            $name = SessionMiddleware::DEFAULT_SESSION_NAME;
            $id = uniqid();
            $handler = $manager->createHandlerByConfig();
            $session = new SessionStore($name, $handler, $id);
            $manager->setSession($session);
            \co::sleep(0.5);
            $this->assertEquals($session, $manager->getSession());
        });
    }
}