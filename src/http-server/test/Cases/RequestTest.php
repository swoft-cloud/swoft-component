<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\HttpServer;

class RequestTest extends AbstractTestCase
{
    public function testHandleRouter()
    {
        $json = $this->raw('POST', '/handle-router/index')->getBody()->getContents();
        $this->assertEquals('{"status":true}', $json);

        $json = $this->raw('POST', '/handle-router/index/')->getBody()->getContents();
        $this->assertEquals('{"status":true}', $json);

        $json = $this->raw('POST', '/handle-router/test')->getBody()->getContents();
        $this->assertEquals('{"status":true,"route":"test"}', $json);

        $json = $this->raw('POST', '/handle-router/test/')->getBody()->getContents();
        $this->assertEquals('{"status":true,"route":"test2"}', $json);
    }
}
