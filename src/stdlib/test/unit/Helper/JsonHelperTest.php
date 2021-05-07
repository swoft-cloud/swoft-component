<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class JsonHelperTest
 *
 * @since 2.0
 */
class JsonHelperTest extends TestCase
{
    public function testEncode(): void
    {
        $json = JsonHelper::encode(['Swoft' => 'Is the best php framework in china']);
        $this->assertSame('{"Swoft":"Is the best php framework in china"}', $json);
    }

    public function testDecode(): void
    {
        $json = JsonHelper::decode('{"Swoft":"Is the best php framework in china"}', true);
        $this->assertSame(['Swoft' => 'Is the best php framework in china'], $json);

        $obj = JsonHelper::decode('{"Swoft":"Is the best php framework in china"}');
        $this->assertSame('Is the best php framework in china', $obj->Swoft);
    }
}
