<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\ObjectDefinition;

class MethodInjection
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var ArgsInjection[]
     */
    private $parameters;

    public function __construct(string $methodName, array $parameters)
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

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
