<?php declare(strict_types=1);

namespace Swoft\Http\Server\Formatter;

use Psr\Http\Message\ResponseInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Response;

/**
 * Class HtmlResponseFormatter
 *
 * @Bean()
 *
 * @since 2.0
 */
class HtmlResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @param Response|ResponseInterface $response
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function format(Response $response): Response
    {
        $response = $response
            ->withoutHeader('Content-Type')
            ->withAddedHeader('Content-Type', ContentType::TEXT);

        $data = $response->getData();
        if ($data !== null) {
            return $response->withContent($data);
        }

        return $response;
    }
}