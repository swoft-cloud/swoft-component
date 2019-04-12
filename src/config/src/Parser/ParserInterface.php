<?php declare(strict_types=1);

namespace Swoft\Config\Parser;

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