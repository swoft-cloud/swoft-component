<?php declare(strict_types=1);


namespace Swoft\Test\Concern;


use PHPUnit\Framework\Assert;

trait RpcResponseAssertTrait
{
    /**
     * @var \Swoft\Rpc\Response
     */
    protected $returnResponse;

    /**
     * Assert
     */
    public function assertSuccess(): void
    {
        Assert::assertTrue($this->returnResponse->getError() == null);
    }

    /**
     * Assert
     */
    public function assertFail(): void
    {
        Assert::assertTrue($this->returnResponse->getError() != null);
    }

    /**
     * @param int $code
     */
    public function assertErrorCode(int $code): void
    {
        $error = $this->returnResponse->getError();
        if ($error === null) {
            Assert::assertTrue(false);
            return;
        }

        $errorCode = $error['code'] ?? 0;
        Assert::assertEquals($code, $errorCode);
    }

    /**
     * @param string $message
     */
    public function assertErrorMessage(string $message): void
    {
        $error = $this->returnResponse->getError();
        if ($error === null) {
            Assert::assertTrue(false);
            return;
        }

        $errorMessage = $error['message'] ?? 0;
        Assert::assertEquals($message, $errorMessage);
    }

    /**
     * @param array $data
     */
    public function assertEqualJsonResult(array $data): void
    {
        Assert::assertEquals($data, $this->returnResponse->getResult());
    }
}