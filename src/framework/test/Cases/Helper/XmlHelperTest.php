<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/4/20
 * Time: 下午3:07
 * @author April2 <ott321@yeah.net>
 */
namespace SwoftTest\Helper;

use Swoft\Helper\XmlHelper;
use SwoftTest\AbstractTestCase;

class XmlHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @covers XmlHelper::encode()
     */
    public function testEncode(){
        $data = ['touser'=>['hello'=>'world'],'test'=>1];
        $res = XmlHelper::encode($data);
        $this->assertStringStartsWith("<xml><touser>",$res);
        $this->assertStringEndsWith("</test></xml>",$res);
    }

    /**
     * @test
     * @covers XmlHelper::decode()
     */
    public function testDecode(){
        $xml = "<xml><touser><hello><![CDATA[world]]></hello></touser><test>1</test></xml>";
        $res = XmlHelper::decode($xml);
        $this->assertArraySubset(['touser' => ['hello' => 'world']], $res);
    }
}