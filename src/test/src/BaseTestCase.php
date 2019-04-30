<?php declare(strict_types=1);

namespace Swoft\Test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTestCase
 *
 * @since 2.0
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * @param string $haystack
     * @param string $needle
     */
    public function assertContainString(string $haystack, string $needle): void
    {
        $exist = \strpos($haystack, $needle) !== false;

        Assert::assertTrue($exist);
    }

    /**
     * @param array $haystack
     * @param mixed $needle
     */
    public function assertArrayContainValue(array $haystack, $needle): void
    {
        $exist = \in_array($needle, $haystack, true);

        Assert::assertTrue($exist);
    }
}
