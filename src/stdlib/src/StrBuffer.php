<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-24
 * Time: 9:17
 */

namespace Toolkit\StrUtil;

/**
 * Class StrBuffer
 * @package Toolkit\StrUtil
 */
final class StrBuffer
{
    /**
     * @var string
     */
    private $body;

    public function __construct(string $content = '')
    {
        $this->body = $content;
    }

    /**
     * @param string $content
     */
    public function write(string $content): void
    {
        $this->body .= $content;
    }

    /**
     * @param string $content
     */
    public function append(string $content): void
    {
        $this->write($content);
    }

    /**
     * @param string $content
     */
    public function prepend(string $content): void
    {
        $this->body = $content . $this->body;
    }

    /**
     * clear
     */
    public function clear(): void
    {
        $this->body = '';
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
