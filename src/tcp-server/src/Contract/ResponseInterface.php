<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Contract;

/**
 * Interface ResponseInterface
 *
 * @since 2.0.3
 */
interface ResponseInterface
{
    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     */
    public function setData($data): void;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @param string $content
     */
    public function setContent(string $content): void;
}
