<?php declare(strict_types=1);


namespace Swoft\Http\Server\Parser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Contract\RequestParserInterface;
use Swoft\Stdlib\Helper\XmlHelper;

/**
 * Class XmlRequestParser
 *
 * @Bean()
 *
 * @since 2.0
 */
class XmlRequestParser implements RequestParserInterface
{
    /**
     * @param string $content
     *
     * @return mixed
     */
    public function parse(string $content)
    {
        try {
            $parsedBody = XmlHelper::decode($content);
        } catch (\Throwable $e) {
            $parsedBody = $content;
        }

        return $parsedBody;
    }
}