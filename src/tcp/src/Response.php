<?php declare(strict_types=1);

namespace Swoft\Tcp;

use Swoft\Tcp\Contract\ResponseInterface;

/**
 * Class Response
 *
 * @since 2.0.4
 */
class Response implements ResponseInterface
{
    // private $body;
    public const OK = 0;

    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var string
     */
    protected $msg = 'ok';

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
    protected $content;

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
}
