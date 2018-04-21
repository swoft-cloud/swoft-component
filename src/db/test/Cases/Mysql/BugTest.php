<?php

namespace SwoftTest\Db\Cases\Mysql;

use SwoftTest\Db\Cases\AbstractMysqlCase;
use SwoftTest\Db\Testing\Entity\User;

/**
 * BugTest
 */
class BugTest extends AbstractMysqlCase
{
    /**
     * @dataProvider relationProider
     *
     * @param int $uid
     */
    public function testQueryCount(int $uid)
    {
        $count = User::query()->count()->getResult();
        $this->assertTrue($count > 0);
    }

    public function testQueryNull()
    {
        $result = User::query()->where('id', 0)->one()->getResult();
        $this->assertEquals($result, null);
    }
}