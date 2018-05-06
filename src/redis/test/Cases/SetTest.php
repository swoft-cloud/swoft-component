<?php

namespace SwoftTest\Redis;

/**
 * SetTest
 */
class SetTest extends AbstractTestCase
{
    public function testSaddAndSMembers()
    {
        $key    = uniqid();
        $value1 = uniqid();
        $value2 = uniqid();
        $value3 = uniqid();
        $value4 = uniqid();

        $this->redis->sAdd($key, $value1, $value2, $value3);
        $this->redis->sAdd($key, $value4);

        $values = [$value1, $value2, $value3, $value4];

        $members = $this->redis->sMembers($key);
        sort($members);
        sort($values);

        $this->assertEquals($members, $values);
    }

    public function testSaddAndSMembersByCo()
    {
        go(function () {
            $this->testSaddAndSMembers();
        });
    }

}