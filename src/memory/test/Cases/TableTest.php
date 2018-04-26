<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Memory;

use Swoft\Memory\Table;
use Swoft\Memory\Exception;

/**
 * Test
 */
class TableTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function tableName()
    {
        $table = new Table();
        $this->assertEquals('', $table->getName());
        $name = 'test';
        $table->setName($name);
        $this->assertEquals($name, $table->getName());
        $this->assertEquals($name, (new Table($name))->getName());
    }

    /**
     * @test
     */
    public function tableSize()
    {
        $size = '1024';
        $table = new Table('', $size);
        $this->assertEquals($size, $table->getSize());
        $table->setSize($size * 2);
        $this->assertEquals($size * 2, $table->getSize());
    }

    /**
     * @test
     */
    public function create()
    {
        $columns = [
            'string' => [Table::TYPE_STRING, 100],
            'int'    => [Table::TYPE_INT, 8],
            'float'  => [Table::TYPE_FLOAT, 8],
        ];
        $table = new Table('test', 1024, $columns);
        $this->assertFalse($table->isCreate());
        $result = $table->create();
        $this->assertTrue($result);
        $this->assertTrue($table->isCreate());
        $this->assertException(function () use ($table) {
            $table->create();
        }, Exception\RuntimeException::class);
    }

    /**
     * @test
     */
    public function columns()
    {
        $columns = [
            'string' => [Table::TYPE_STRING, 100],
            'int'    => [Table::TYPE_INT, 8],
            'float'  => [Table::TYPE_FLOAT, 8],
        ];
        $table = new Table('test', 1024);
        $this->assertEquals([], $table->getColumns());
        $table->setColumns($columns);
        $this->assertEquals($columns, $table->getColumns());
        $table = new Table('test', 1024, $columns);
        $this->assertEquals($columns, $table->getColumns());

        // Add new column
        $result = $table->column('newColumn', Table::TYPE_STRING, 10);
        $this->assertTrue($result);

        // Int type
        $intColumns = [
            'int1' => [Table::TYPE_INT, 1],
            'int2' => [Table::TYPE_INT, 2],
            'int4' => [Table::TYPE_INT, 4],
            'int8' => [Table::TYPE_INT, 8],
            'int9' => [Table::TYPE_INT, 9]
        ];
        $table = new Table('test', 1024, $intColumns);
        $this->assertEquals(array_replace($intColumns, ['int9' => [Table::TYPE_INT, 4]]), $table->getColumns());

        // Float type
        $floatColumns = [
            'float1' => [Table::TYPE_FLOAT, 1],
            'float2' => [Table::TYPE_FLOAT, 2],
        ];
        $table = new Table('test', 1024, $floatColumns);
        $this->assertEquals(array_replace($floatColumns, [
            'float1' => [Table::TYPE_FLOAT, 8],
            'float2' => [Table::TYPE_FLOAT, 8],
        ]), $table->getColumns());

        // String type
        $this->assertExceptionMulti([
            function () {
                $columns = [
                    'string-1' => [Table::TYPE_STRING, -1],
                ];
                new Table('test', 1024, $columns);
            },
            function () {
                $columns = [
                    'string-1' => [Table::TYPE_STRING, -1],
                ];
                $table = new Table('test', 1024);
                $table->setColumns($columns);
            }
        ], Exception\InvalidArgumentException::class);

        // Undefined type
        $this->assertExceptionMulti([
            function () {
                $columns = [
                    'undefined' => [4, 1],
                ];
                new Table('test', 1024, $columns);
            },
            function () {
                $columns = [
                    'undefined' => [4, 1],
                ];
                $table = new Table('test', 1024);
                $table->setColumns($columns);
            },
        ], Exception\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function operations()
    {
        $columns = [
            'string' => [Table::TYPE_STRING, 100],
            'int'    => [Table::TYPE_INT, 8],
            'float'  => [Table::TYPE_FLOAT, 8],
        ];
        $table = new Table('test', 1024, $columns);

        /**
         * Before memory table create
         */
        $this->assertExceptionMulti([
            function () use ($table) {
                $table->get('test');
            },
            function () use ($table) {
                $table->set('test', ['string' => 'test']);
            },
            function () use ($table) {
                $table->exist('test');
            },
            function () use ($table) {
                $table->del('test');
            },
            function () use ($table) {
                $table->del('test');
            },
            function () use ($table) {
                $table->incr('test', 'string', 1);
            },
            function () use ($table) {
                $table->decr('test', 'string', 1);
            },
        ], Exception\RuntimeException::class);

        $table->create();

        /**
         * After memory table created
         */
        $columns = [
            'string' => $string = 'test',
            'int'    => $int = 123,
            'float'  => $float = 1.23,
        ];
        $key = 'test';
        $notExistKey = 'notExist';
        $this->assertFalse($table->exist($key));
        $result = $table->set($key, $columns);
        $this->assertTrue($result);
        $this->assertTrue($table->exist($key));
        $this->assertEquals($string, $table->get($key, 'string'));
        $this->assertEquals($int, $table->get($key, 'int'));
        $this->assertEquals($float, $table->get($key, 'float'));
        $this->assertFalse($table->get($key, $notExistKey));
        $this->assertFalse($table->get($notExistKey, 'notExist'));
        $this->assertEquals($columns, $table->get($key));
        $this->assertEquals($columns, $table->get($key, null));
        if (SWOOLE_VERSION < '2.1.3') {
            $this->assertFalse($table->get($key, false));
        }

        /**
         * Incr and Decr
         */
        $step = 1;
        $increasedInt = $table->incr($key, 'int', $step);
        $expectedInt = $int + $step;
        $this->assertEquals($expectedInt, $increasedInt);
        $increasedInt = $table->incr($key, 'int', $step * 2);
        $expectedInt += $step * 2;
        $this->assertEquals($expectedInt, $increasedInt);

        $currentInt = $table->get($key, 'int');
        $decreasedInt = $table->decr($key, 'int', $step);
        $expectedInt = $currentInt - $step;
        $this->assertEquals($expectedInt, $decreasedInt);
        $decreasedInt = $table->decr($key, 'int', $step * 2);
        $expectedInt -= $step * 2;
        $this->assertEquals($expectedInt, $decreasedInt);

        /**
         * Delete
         */
        $this->assertTrue($table->del($key));
        $this->assertFalse($table->del($notExistKey));
    }

}
