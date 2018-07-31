<?php

namespace SwoftTest\HttpServer;

use Swoft\Helper\JsonHelper;

class ValidatorTest extends AbstractTestCase
{
    public function testDemo()
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $raw = JsonHelper::encode([
            'test' => [
                'id' => 1
            ]
        ]);
        $res = $this->raw('POST', '/validator/json', [], $headers, $raw)->getBody()->getContents();
        $this->assertEquals('[1,"limx"]', $res);

        $headers = [
            'Content-Type' => 'application/json;charset=UTF-8'
        ];
        $raw = JsonHelper::encode([
            'test' => [
                'id' => 1
            ]
        ]);
        $res = $this->raw('POST', '/validator/json', [], $headers, $raw)->getBody()->getContents();
        $this->assertEquals('[1,"limx"]', $res);
    }


}
