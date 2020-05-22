<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Context;

use Swoft\Contract\ContextInterface;
use Swoft\Stdlib\Concern\DataPropertyTrait;

/**
 * Class AbstractContext
 *
 * @since 2.0
 */
abstract class AbstractContext implements ContextInterface
{
    use DataPropertyTrait;

    /**
     * Clear context data
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
