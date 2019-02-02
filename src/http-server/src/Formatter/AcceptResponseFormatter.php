<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;
use Swoft\Stdlib\Helper\StringHelper;

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
        $request    = context()->getRequest();
        $accepts    = $request->getHeader('accept');
        $acceptType = \current($accepts);

        $type = '';
        foreach ($this->formats as $contentType => $formatType) {
                $type = $formatType;
                break;
        }

        if (!empty($type)) {
            $response->setFormat($type);
        }

        return $response;
    }
}