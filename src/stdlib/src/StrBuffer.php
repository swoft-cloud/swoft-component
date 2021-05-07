<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Stdlib;

/**
 * Class StrBuffer
 */
final class StrBuffer
{
    /**
     * @var string
     */
    private $body;

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    /**
     * Class constructor.
     *
     * @param string $content
     */
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
     * clear data
     */
    public function clear(): string
    {
        $string = $this->body;
        // clear
        $this->body = '';

        return $string;
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
