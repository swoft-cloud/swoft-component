<?php declare(strict_types=1);


namespace SwoftTest\Db;


use Swoft\Test\TestApplication;

/**
 * Class TestCase
 *
 * @since 2.0
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestApplication
     */
    protected $application;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->application = new TestApplication();
        $this->application->setBeanFile(__DIR__ . '/bean.php');
        $this->application->run();
    }
}