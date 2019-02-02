<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class JsonResponseFormatter
 *
 * @Bean()
 *
 * @since 2.0
 */
class JsonResponseFormatter implements ResponseFormatterInterface
{
    /**
     * Content type
     */
    const CONTENT_TYPE = 'application/json';

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

        if ($data !== null && (Arr::isArrayable($data) || is_string($data))) {
            $data    = is_string($data) ? ['data' => $data] : $data;
            $content = JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
            return $response->withContent($content);
        }

        return $response->withContent('{}');
    }
}