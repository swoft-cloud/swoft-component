<?php declare(strict_types=1);


namespace Swoft\Task;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Task\Contract\ResponseInterface;

/**
 * Class Response
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response implements ResponseInterface
{
    use PrototypeTrait;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var int|null
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage = '';

    /**
     * @param null   $result
     * @param null   $errorCode
     * @param string $errorMessage
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new($result = null, $errorCode = null, $errorMessage = ''): self
    {
        $instance = self::__instance();

        $instance->result       = $result;
        $instance->errorCode    = $errorCode;
        $instance->errorMessage = $errorMessage;

        return $instance;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @param int|null $errorCode
     */
    public function setErrorCode(?int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getResponseData(): string
    {
        return Packet::packResponse($this->result, $this->errorCode, $this->errorMessage);
    }

    /**
     * Send task
     */
    public function send(): void
    {
        \Swoft::swooleServer()->finish($this->getResponseData());
    }
}