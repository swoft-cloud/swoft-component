<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Response;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class HtmlResponseFormatter
 *
 * @since 2.0
 *
 * @Bean()
 */
class HtmlResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @param Response $response
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function format(Response $response): Response
    {
        $response = $response
            ->withoutHeader(ContentType::KEY)
            ->withAddedHeader(ContentType::KEY, ContentType::HTML);

        $data = $response->getData();

        if ($data !== null && (Arr::isArrayable($data) || is_string($data))) {
            $data    = \is_string($data) ? ['data' => $data] : $data;
            $content = JsonHelper::encode($data);
            return $response->withContent($content);
        }

        return $response;
    }
}
