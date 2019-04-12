<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-01
 * Time: 23:06
 */

namespace Swoft\Contract;

/**
 * Interface RouterInterface - base interface for service router
 * @since 2.0
 */
interface RouterInterface
{
    /**
     * Found route
     */
    public const FOUND     = 1;

    /**
     * Not found
     */
    public const NOT_FOUND = 2;
}
