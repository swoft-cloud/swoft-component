<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 17:32
 */

namespace Swoft;

use Swoft\Contract\ComponentInterface;

/**
 * Class SwoftComponent
 * @package Swoft
 */
abstract class SwoftComponent implements ComponentInterface
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function coreBean(): array
    {
        return [];
    }
}
