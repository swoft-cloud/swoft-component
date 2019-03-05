<?php declare(strict_types=1);


namespace SwoftTest\Db\Eloquent;


use SwoftTest\Db\Entity\User;
use SwoftTest\Db\TestCase;

/**
 * Class ModelTest
 *
 * @since 2.0
 */
class ModelTest extends TestCase
{
    /**
     * Save
     */
    public function testSave()
    {
        go(function () {
            $user = User::new();
            $user->setAge(100);
            $user->setUserDesc('desc');

            // Save result
            $result = $user->save();
            $this->assertTrue($result);

            // Insert id
            $this->assertGreaterThan(1, $user->getId());

            $user2 = User::new();
            $user2->setAge(100);

            // Save result
            $result2 = $user2->save();
            $this->assertTrue($result2);
        });
    }

    public function testFind()
    {
//        $result = User::findMany([22]);
//        var_dump($result);

//        var_dump(User::where('age', '=', 100)->select('id')->selectSub());
    }
}