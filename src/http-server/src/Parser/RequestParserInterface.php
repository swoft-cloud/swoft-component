<?php declare(strict_types=1);


namespace Swoft\Http\Server\Parser;

use Swoft\Http\Server\Request;

/**
 * Class RequestParserInterface
 *
 * @since 2.0
 */
interface RequestParserInterface
{
    /**
     * @param string $content
     *
     * @return mixed
     */
    public function parse(string $content);
}