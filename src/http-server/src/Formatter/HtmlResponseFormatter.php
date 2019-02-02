<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;

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
     * content type
     */
    const CONTENT_TYPE = 'text/html';

    /**
     * @param Response $response
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function format(Response $response): Response
    {
        $response = $response->withoutHeader('Content-Type')
            ->withAddedHeader('Content-Type', self::CONTENT_TYPE);

        $data = $response->getData();
        if ($data !== null) {
            return $response->withContent($data);
        }

        return $response;
    }
}