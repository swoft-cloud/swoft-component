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
    public function testBeforeSaveAndAfterSave()
    {
        $user = new User([
            'name' => 'name',
            'sex' => 1,
            'age' => 99,
        ]);

        $user->save()->getResult();

        $this->assertEquals('Set by beforeSaveListener', $user->getDesc());
        $this->assertEquals(100, $user->getAge());
    }

    public function testBeforeUpdate()
    {
        $user = new User([
            'name' => 'name',
            'sex' => 1,
            'age' => 99,
        ]);

        $user->save()->getResult();

        $user->update();

        $this->assertEquals('Update by beforeUpdateLinstener', $user->getDesc());
    }

    public function testAfterDelete()
    {
        $user = new User([
            'name' => 'name',
            'sex' => 1,
            'age' => 99,
        ]);

        $user->save()->getResult();

        $user->delete();

        $this->assertEquals('Delete by afterDeleteListener', $user->getDesc());
    }
}