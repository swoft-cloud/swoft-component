<?php declare(strict_types=1);


namespace SwoftTest\Db;


use Swoft\Db\DB;

class test
{
    public function test()
    {
        DB::pool()->table('blog')->where('id', '=', 1)->first();
        DB::pool()->raw();

        $user = new User();
    }
}