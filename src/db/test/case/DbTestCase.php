<?php declare(strict_types=1);


namespace SwoftTest\Db;


use PHPUnit\Framework\TestCase;
use Swoft\Test\TestApplication;

/**
 * Class DbTestCase
 *
 * @since 2.0
 */
class DbTestCase extends TestCase
{
    /**
     * @var TestApplication
     */
    protected $application;

    public function setUp()
    {
        $this->application = new TestApplication();
        $this->application->setBeanFile();
    }
}