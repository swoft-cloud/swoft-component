<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean\Definition;

/**
 * Class MethodInjection
 *
 * @since 2.0
 */
class MethodInjection
{
    /**
     * Method name
     *
     * @var string
     */
    private $methodName;

    /**
     * Method parameters
     *
     * @var ArgsInjection[]
     */
    private $parameters = [];

    /**
     * MethodInjection constructor.
     *
     * @param string          $methodName
     * @param ArgsInjection[] $parameters
     */
    public function __construct(string $methodName, array $parameters)
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return ArgsInjection[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
