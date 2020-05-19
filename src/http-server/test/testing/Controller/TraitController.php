<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class TraitController
 *
 * @since 2.0
 */
trait TraitController
{
    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function traitMethod(): array
    {
        return ['traitMethod'];
    }
}
