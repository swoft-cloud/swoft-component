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
use Swoft\Stdlib\Helper\ObjectHelper;

/**
 * Class ObjectHelperTest
 *
 */
class ObjectHelperTest extends TestCase
{
    public function test(): void
    {
        $samples = [
            'int'    => 0,
            'string' => '',
            'float'  => 0,
            'bool'   => false,
        ];

        foreach ($samples as $sample => $want) {
            $this->assertSame($want, ObjectHelper::getDefaultValue($sample));
        }
    }
}
