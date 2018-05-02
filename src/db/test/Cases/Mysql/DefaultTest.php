<?php

namespace SwoftTest\Db\Cases\Mysql;

use SwoftTest\Db\Cases\AbstractMysqlCase;
use SwoftTest\Db\Testing\Entity\Detable;

/**
 * DefaultTest
 */
class DefaultTest extends AbstractMysqlCase
{
    public function testAdd()
    {
        $detable = new Detable();
        $detable->setShortName('');
        $detable->setUtime(date('Y-m-d H:i:s'));
        $detable->setBooks(12);

        $did = $detable->save()->getResult();

        $this->assertTrue($did >= 1);

        $detable              = new Detable();
        $detable['shortName'] = '';
        $detable['utime']     = date('Y-m-d H:i:s');
        $detable['books']     = 12;

        $did2 = $detable->save()->getResult();
        $this->assertTrue($did2 >= 1);

        $detable = new Detable();

        $data              = [];
        $data['shortName'] = '';
        $data['utime']     = date('Y-m-d H:i:s');
        $data['books']     = 12;
        $data['dName']     = 'default name';

        $detable->fill($data);
        $did3 = $detable->save()->getResult();
        $this->assertTrue($did3 >= 1);
    }

    public function testAddByCo()
    {
        go(function () {
            $this->testAdd();
        });
    }

    /**
     * @expectedException \Swoft\Db\Exception\MysqlException
     */
    public function testAddRequireException()
    {
        $detable = new Detable();
        $detable->setShortName('');
        $detable->setUtime(date('Y-m-d H:i:s'));

        $did = $detable->save()->getResult();
    }

    public function testUpdate()
    {
        $detable = new Detable();
        $detable->setShortName('');
        $detable->setUtime(date('Y-m-d H:i:s'));
        $detable->setBooks(12);

        $did = $detable->save()->getResult();

        /* @var Detable $newDetable */
        $newDetable = Detable::findById($did)->getResult();
        $newDetable->setDName('dname');
        $newDetable->setCount(10);
        $newDetable->setAmount(12.1);

        $result = $newDetable->update()->getResult();
        $this->assertEquals(1, $result);

        $detable = Detable::findById($did)->getResult();
        $this->assertEquals($detable['amount'], 12.1);

        $ret = Detable::updateOne(['d_name' => 'dname...'], ['s_id' => $did])->getResult();
        $this->assertEquals(1, $result);

        $detable = Detable::findById($did)->getResult();
        $this->assertEquals($detable['dName'], 'dname...');

    }

    public function testUpdateByCo()
    {
        go(function () {
            $this->testUpdate();
        });
    }

}