<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Helper;

use Swoft\Auth\Helper\ErrorCode;
use Swoft\Auth\Helper\ErrorCodeHelper;
use SwoftTest\Auth\AbstractTestCase;

class ErrorCodeHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @covers ErrorCodeHelper::get()
     */
    public function testGet()
    {
        $helper = new ErrorCodeHelper();
        $arr = $helper->get(ErrorCode::ACCESS_DENIED);
        $this->assertArrayHasKey('statusCode', $arr);
    }
}
