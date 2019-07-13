<?php declare(strict_types=1);

namespace Swoft\Tcp;

use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Tcp\Contract\ResponseInterface;

/**
 * Class Response
 *
 * @since 2.0.4
 */
class Response implements ResponseInterface
{
    /**
     * @var int
     */
    protected $code = self::OK;

    /**
     * @var string
     */
    protected $msg = self::DEFAULT_MSG;

    /**
     * @var array
     */
    protected $ext = [];

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $content = '';

    /**
     * Class constructor.
     *
     * @param int    $code
     * @param string $msg
     * @param mixed  $data
     */
    public function __construct(int $code = self::OK, string $msg = self::DEFAULT_MSG, $data = null)
    {
        $this->code = $code;
        $this->msg  = $msg;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'msg'  => $this->msg,
            'data' => $this->data,
            'ext'  => $this->ext,
        ];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return JsonHelper::encode($this->toArray());
    }

    /**
     * @return string
     */
    public function getDataString(): string
    {
        if (!$this->data) {
            return '';
        }

        if (is_scalar($this->data)) {
            return (string)$this->data;
        }

        return JsonHelper::encode($this->data);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getExt(): array
    {
        return $this->ext;
    }

    /**
     * @param array $ext
     */
    public function setExt(array $ext): void
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
