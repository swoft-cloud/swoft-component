<?php

namespace SwoftTest\Db\Cases\Mysql;

use Swoft\Db\Query;
use SwoftTest\Db\Cases\AbstractMysqlCase;
use SwoftTest\Db\Testing\Entity\Prefix;
use SwoftTest\Db\Testing\Entity\User;

/**
 * PrefixTest
 */
class EventTest extends AbstractMysqlCase
{
    public function testBeforeSave()
    {
        $user = new User([
            'name' => 'name',
            'sex' => 1,
            'description' => 'this my desc',
            'age' => 99,
        ]);

        $id = $user->save()->getResult();

        $this->assertEquals('Set by beforeSaveListener', $user->getDesc());
        $this->assertEquals(100, $user->getAge());

    }
}