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
    /** match result status list */
    public const FOUND     = 1;
    public const NOT_FOUND = 2;

    // public function add(string $method, string $path, $handler, array $binds = [], array $opts = []);
    // public function match(...$params): array;
}
