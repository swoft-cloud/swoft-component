<?php declare(strict_types=1);

namespace Swoft\Http\Server\Parser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Contract\RequestParserInterface;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class JsonRequestParser
 *
 * @Bean()
 *
 * @since 2.0
 */
class JsonRequestParser implements RequestParserInterface
{
    /**
     * @param string $content
     *
     * @return mixed
     */
    public function parse(string $content)
    {
        try {
            $parsedBody = JsonHelper::decode($content, true);
        } catch (\Exception $e) {
            $parsedBody = $content;
        }

        return $parsedBody;
    }
}