<?php declare(strict_types=1);


use SwoftTest\Db\Unit\TestCase;
use Swoft\Db\Schema\Builder;
use Swoft\Db\Schema\Blueprint;

/**
 * Class BuilderTest
 *
 * @since 2.0
 */
class SchemaBuilderTest extends TestCase
{

    public function getBuilder(): Builder
    {
        $pool    = [
            'db.pool2',
            'db.pool'
        ];
        $builder = Builder::new($pool[array_rand($pool)], null);

        return $builder;
    }

    public function testColumn()
    {
        $builder = $this->getBuilder();

        $res = $builder->getColumnListing('user');

        $this->assertTrue(in_array('id', $res));
        $this->assertTrue(in_array('age', $res));

        $this->assertFalse($builder->hasColumn('user', 'xxxx'));
        $this->assertTrue($builder->hasColumn('user', 'age'));

        $this->assertTrue($builder->hasColumns('user', ['id', 'age', 'user_desc']));
        $this->assertFalse($builder->hasColumns('user', ['id', 'age', 'xxx']));
    }

    public function testTable()
    {
        $builder = $this->getBuilder();
        $this->assertTrue($builder->hasTable('user'));
        $this->assertFalse($builder->hasTable('user' . mt_rand(10, 111)));
    }

    public function testCreate()
    {
        $builder = $this->getBuilder();

        $rename = 'user2';
        $table  = 'user1';

        $builder->dropIfExists($rename);
        $builder->dropIfExists($table);

        $builder->create($table, function (Blueprint $blueprint) {
            $blueprint->integer('id')->primary();
            $blueprint->bigInteger('uid')->index();
            $blueprint->tinyInteger('status')->index('idx_status');
            // todo
            //$blueprint->renameColumn('id', 'user_id');

            $blueprint->uuid('uuid');
            $blueprint->integer('create_time');
        });

        // rename idx
        $builder->table($table, function (Blueprint $blueprint) use ($rename) {
            $blueprint->renameIndex('idx_status', 'idx_sta');
            // rename table
            $blueprint->rename($rename);
        });


        $this->assertTrue(true);
    }
}
