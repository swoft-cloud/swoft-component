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
use Swoft\Stdlib\Helper\JsonHelper;
use Throwable;

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
     * @throws HttpServerException
     */
    public function parse(string $content)
    {
        try {
            $parsedBody = JsonHelper::decode($content, true);
        } catch (Throwable $e) {
            throw new HttpServerException(sprintf('Request body parse to json error(%s), body=%s', $e->getMessage(),
                    $content));
        }

        return $parsedBody;
    }
}
