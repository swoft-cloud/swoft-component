<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;

/**
 * Namespace compatibility with previous versions, which non-componentization version
 * @Bootstrap(order=1)
 */
class CompPreviousVersionNamespace implements Bootable
{
    /**
     * @return void
     */
    public function bootstrap()
    {
        $map = [
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}
