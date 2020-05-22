<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Config\Contract;

use Swoft\Config\Config;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * Parse files
     *
     * @param Config $config
     *
     * @return array
     */
    public function parse(Config $config):array ;
}
