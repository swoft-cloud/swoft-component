<?php declare(strict_types=1);

namespace Swoft\Http\Server\Formatter;

use function context;
use function current;
use function strpos;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Response;

/**
 * Class AcceptResponseFormatter
 *
 * @Bean("acceptFormatter")
 *
 * @since 2.0
 */
class AcceptResponseFormatter implements ResponseFormatterInterface
{
    /**
     * Formats
     *
     * @var array
     *
     * @example
     * [
     *   'application/json' => Response::FORMAT_JSON,
     *   'application/xml' => Response::FORMAT_JSON,
     * ]
     */
    protected $formats = [];

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function format(Response $response): Response
    {
        $request = context()->getRequest();
        $accepts = $request->getHeader('accept');

        $responeContentType = $response->getHeaderLine(ContentType::KEY);

        // Format by user response content type
        if (!empty($responeContentType)) {
            $format = $this->getResponseFormat($responeContentType);
            if (!empty($format)) {
                $response->setFormat($format);
            } else {
                $response->setFormat('');
            }

            return $response;
        }

        // Fix empty bug
        if (empty($accepts)) {
            return $response;
        }

        // Accept type to format, default is json
        $acceptType = current($accepts);
        $format = $this->getResponseFormat($acceptType);

        if (!empty($format)) {
            $response->setFormat($format);
        }

        return $response;
    }

    /**
     * Match format from all formats
     *
     * @param string $responseContentType
     *
     * @return string
     */
    private function getResponseFormat(string $responseContentType): string
    {
        $format = '';
        foreach ($this->formats as $contentType => $formatType) {
            if (strpos($responseContentType, $contentType) === 0) {
                $format = $formatType;
                break;
            }
        }

        return $format;
    }
}