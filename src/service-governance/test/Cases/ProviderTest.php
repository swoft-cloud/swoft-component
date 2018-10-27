<?php
namespace SwoftTest\Sg;


class ProviderTest extends AbstractTestCase
{
    public function testRegister()
    {
        $res = provider()->select()->registerService();
        $this->assertTrue($res);
    }
}