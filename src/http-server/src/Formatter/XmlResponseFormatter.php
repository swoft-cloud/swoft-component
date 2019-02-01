<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;
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
     * Content type
     */
    const CONTENT_TYPE = 'application/xml';

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
            ->withAddedHeader('Content-Type', Response::CONTENT_XML);

        $data = $response->getData();

        if ($data !== null && is_string($data)) {
            $content = XmlHelper::decode($data);
            return $response->withContent($content);
        }

        return $response;
    }
}