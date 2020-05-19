<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Formatter;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Contract\ResponseInterface;
use Swoft\Http\Message\Response;
use Swoft\Stdlib\Helper\XmlHelper;

/**
 * Class XmlResponseFormatter
 *
 * @Bean()
 *
 * @since 2.0
 */
class XmlResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @param Response|ResponseInterface $response
     *
     * @return Response
     */
    public function format(Response $response): Response
    {
        $response = $response->withoutHeader(ContentType::KEY)->withAddedHeader(ContentType::KEY, ContentType::XML);

        $data = $response->getData();

        if ($data !== null) {
            $data    = !is_array($data) ? [$data => $data] : $data;
            $content = XmlHelper::encode($data);
            return $response->withContent($content);
        }

        return $response;
    }
}
