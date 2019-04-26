<?php declare(strict_types=1);

namespace Swoft\Http\Server\Formatter;

use Swoft\Bean\Annotation\Mapping\Bean;
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
        $format  = '';
        $request = \context()->getRequest();
        $accepts = $request->getHeader('accept');

        // Fix empty bug
        if(empty($accepts)){
            return $response;
        }

        $acceptType = \current($accepts);
        foreach ($this->formats as $contentType => $formatType) {
            if (\strpos($acceptType, $contentType) === 0) {
                $format = $formatType;
                break;
            }
        }

        if (!empty($format)) {
            $response->setFormat($format);
        }

        return $response;
    }
}