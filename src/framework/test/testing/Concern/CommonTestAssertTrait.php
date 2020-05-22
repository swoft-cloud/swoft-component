<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Testing\Concern;

use Closure;
use PHPUnit\Framework\Assert;
use Throwable;
use function in_array;
use function strpos;

/**
 * Class CommonTestAssertTrait
 *
 * @since 2.0
 */
trait CommonTestAssertTrait
{
    /**
     * @param string $haystack
     * @param string $needle
     */
    public function assertContainString(string $haystack, string $needle): void
    {
        $exist = strpos($haystack, $needle) !== false;

        Assert::assertTrue($exist, "Failed asserting that \"$haystack\" contains \"$needle\"");
    }

    /**
     * @param array $haystack
     * @param mixed $needle
     */
    public function assertArrayContainValue(array $haystack, $needle): void
    {
        $exist = in_array($needle, $haystack, true);

        Assert::assertTrue($exist);
    }

    /**
     * @param array $haystack
     * @param mixed $needle
     */
    public function assertArrayNotContainValue(array $haystack, $needle): void
    {
        $exist = in_array($needle, $haystack, true);

        Assert::assertFalse($exist);
    }

    /**
     * Usage:
     *
     *  $this->assetException(function() use($object) {
     *      $object->someMethod();
     *  }, XXException::class);
     *
     * @param Closure $closure
     * @param string  $wantErrClass
     * @param string  $wantErrMsg
     * @param int     $wantErrCode
     */
    public function assetException(
        Closure $closure,
        string $wantErrClass,
        string $wantErrMsg = '',
        int $wantErrCode = 0
    ): void {
        $e = null;
        try {
            $closure();
        } catch (Throwable $e) {
        }

        Assert::assertNotNull($e);

        if ($wantErrClass) {
            /** @var Throwable $e */
            Assert::assertSame($wantErrClass, get_class($e));
        }

        if ($wantErrMsg) {
            Assert::assertSame($wantErrMsg, $e->getMessage());
        }

        if ($wantErrCode) {
            Assert::assertSame($wantErrCode, $e->getCode());
        }
    }

    /**
     * @param Closure $closure
     * @param string  $wantErrMsg
     */
    public function assetExceptionWithMessage(Closure $closure, string $wantErrMsg): void
    {
        $this->assetException($closure, '', $wantErrMsg);
    }

    /**
     * @param Closure $closure
     * @param int     $wantCode
     */
    public function assetExceptionWithCode(Closure $closure, int $wantCode): void
    {
        $this->assetException($closure, '', '', $wantCode);
    }

    /**
     * @param Closure $closure
     * @param string  $needle
     */
    public function assetExceptionContainMessage(Closure $closure, string $needle): void
    {
        $e = null;
        try {
            $closure();
        } catch (Throwable $e) {
        }

        Assert::assertNotNull($e);
        $this->assertContainString($e->getMessage(), $needle);
    }
}
