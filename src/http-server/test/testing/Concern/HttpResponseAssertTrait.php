<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing\Concern;

use PHPUnit\Framework\Assert;
use Swoft\Stdlib\Helper\JsonHelper;
use function strpos;

/**
 * Class HttpResponseAssertTrait
 *
 * @since 2.0
 */
trait HttpResponseAssertTrait
{
    /**
     * @return HttpResponseAssertTrait
     */
    public function assertSuccess(): self
    {
        Assert::assertEquals($this->status, self::STATUS_SUCCESS);

        return $this;
    }

    /**
     * @return HttpResponseAssertTrait
     */
    public function assertFail(): self
    {
        Assert::assertNotEquals($this->status, self::STATUS_SUCCESS);

        return $this;
    }

    /**
     * @param int $status
     *
     * @return HttpResponseAssertTrait
     */
    public function assertEqualStatus(int $status): self
    {
        Assert::assertEquals($status, $this->status);

        return $this;
    }

    /**
     * @param string $key
     * @param string $values
     *
     * @return HttpResponseAssertTrait
     */
    public function assertEqualHeader(string $key, string $values): self
    {
        Assert::assertTrue(isset($this->header[$key]));

        $headerLine = $this->header[$key] ?? '';
        Assert::assertEquals($values, $headerLine);

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return HttpResponseAssertTrait
     */
    public function assertEqualHeaders(array $headers): self
    {
        Assert::assertEquals($this->getHeaders(), $headers);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return HttpResponseAssertTrait
     */
    public function assertContainHeader(string $key, string $value): self
    {
        Assert::assertTrue(isset($this->header[$key]));

        $headerLine = $this->header[$key] ?? '';
        $hasContain = strpos($headerLine, $value) !== false;

        Assert::assertTrue($hasContain);
        return $this;
    }

    /**
     * @param string $content
     *
     * @return HttpResponseAssertTrait
     */
    public function assertEqualContent(string $content): self
    {
        Assert::assertEquals($content, $this->content);
        return $this;
    }

    /**
     * @param string $content
     *
     * @return HttpResponseAssertTrait
     */
    public function assertContainContent(string $content): self
    {
        $contains = strpos($this->content, $content) !== false;
        Assert::assertTrue($contains);
        return $this;
    }

    /**
     * @param string $content
     *
     * @return HttpResponseAssertTrait
     */
    public function assertNotEqualContent(string $content): self
    {
        Assert::assertNotEquals($content, $this->content);
        return $this;
    }

    /**
     * @param array $data
     *
     * @return HttpResponseAssertTrait
     */
    public function assertEqualJson(array $data): self
    {
        $result = JsonHelper::decode($this->content, true);
        Assert::assertEquals($result, $data);
        return $this;
    }
}
