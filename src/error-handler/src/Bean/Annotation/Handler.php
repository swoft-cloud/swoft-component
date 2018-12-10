<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\ErrorHandler\Bean\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Handler
{
    /**
     * @var string
     */
    private $exception;

    /**
     * Handler constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->exception = $values['value'];
        }

        if (isset($values['exception'])) {
            $this->exception = $values['exception'];
        }
    }

    /**
     * @return string
     */
    public function getException(): string
    {
        return $this->exception;
    }
}
