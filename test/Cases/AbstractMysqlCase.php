<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Db\Cases;

use SwoftTest\Db\Testing\Entity\Count;
use SwoftTest\Db\Testing\Entity\User;

/**
 * DbTestCache
 */
abstract class AbstractMysqlCase extends AbstractTestCase
{
    public function addUsers()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));
        $id  = $user->save()->getResult();
        $id2 = $user->save()->getResult();

        return [
            [[$id, $id2]],
        ];
    }

    public function addUser()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));
        $id = $user->save()->getResult();

        return [
            [$id],
        ];
    }

    public function addUserAndCount()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));
        $id = $user->save()->getResult();

        $count = new Count();
        $count->setUid($id);
        $count->setFans(mt_rand(1000, 10000));
        $count->setFollows(mt_rand(1000, 10000));
        $count->save()->getResult();

        return [
            [$id],
        ];
    }

    public function mysqlProviders()
    {
        return $this->addUsers();
    }

    public function mysqlProvider()
    {
        return $this->addUser();
    }

    public function relationProider()
    {
        return $this->addUserAndCount();
    }
}
