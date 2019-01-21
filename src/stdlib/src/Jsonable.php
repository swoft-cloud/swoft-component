<?php
/**
 * Created by PhpStorm.
 * User: stelin
 * Date: 2019-01-02
 * Time: 17:43
 */

namespace Swoft\Stdlib;

/**
 * Interface Jsonable
 */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}