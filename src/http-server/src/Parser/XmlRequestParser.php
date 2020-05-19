<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Parser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Contract\RequestParserInterface;
use Swoft\Http\Server\Exception\HttpServerException;
use Swoft\Stdlib\Helper\XmlHelper;
use Throwable;

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
     * @throws HttpServerException
     */
    public function parse(string $content)
    {
        try {
            $parsedBody = XmlHelper::decode($content);
        } catch (Throwable $e) {
            throw new HttpServerException(sprintf('Request body parse to xml errro(%s), body=%s', $e->getMessage(),
                    $content));
        }

        return $parsedBody;
    }
}
