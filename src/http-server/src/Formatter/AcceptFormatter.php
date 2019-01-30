<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Request;
use Swoft\Http\Server\Response;

/**
 * Class AcceptFormatter
 *
 * @Bean("acceptFormatter")
 * @since 2.0
 */
class AcceptFormatter implements FormatterInterface
{
    /**
     * Formats
     *
     * @var array
     *
     * @example
     * [
     *   'application/json' => Response::FORMAT_JSON,
     *   'application/xml' => Response::FORMAT_XML,
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

        $format = '';
        foreach ($this->formats as $type => $typeFormat) {
            if (strpos($acceptType, $type) !== false) {
                $format = $typeFormat;
                break;
            }
        }

        if (!empty($format)) {
            $response->setFormat($format);
        }

        return $response;
    }
}