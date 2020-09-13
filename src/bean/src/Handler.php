<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean;

use Swoft\Bean\Contract\HandlerInterface;
use Swoft\Bean\Definition\ObjectDefinition;

/**
 * Class Handler
 *
 * @since 2.0
 */
class Handler implements HandlerInterface
{
    /**
     * Before initialize bean
     *
     * @param string           $beanName
     * @param string           $className
     * @param ObjectDefinition $objDfn
     * @param array            $annotation
     */
    public function beforeInit(string $beanName, string $className, ObjectDefinition $objDfn, array $annotation): void
    {
        // TODO: Implement beforeInit() method.
    }

    /**
     * Class proxy
     *
     * @param string $className
     *
     * @return string
     */
    public function classProxy(string $className): string
    {
        return $className;
    }

    /**
     * Get reference value
     *
     * @param $value
     *
     * @return mixed
     */
    public function getReferenceValue($value)
    {
        return $value;
    }
}
