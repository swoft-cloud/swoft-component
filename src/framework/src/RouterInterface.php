<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-01
 * Time: 23:06
 */

namespace Swoft;

/**
 * Interface RouterInterface - base interface for router
 * @package Swoft
 */
interface RouterInterface
{
    public function add(string $method, string $path, $handler, array $binds = [], array $opts = []);
}
