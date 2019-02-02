<?php

namespace Swoft\Http\Server;

use Swoft\Http\Server\Formatter\HtmlResponseFormatter;
use Swoft\Http\Server\Formatter\JsonResponseFormatter;
use Swoft\Http\Server\Formatter\XmlResponseFormatter;
use Swoft\Http\Server\Parser\JsonRequestParser;
use Swoft\Http\Server\Parser\XmlRequestParser;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Server\Swoole\SwooleEvent;
use function bean;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends \Swoft\AutoLoader
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function coreBean(): array
    {
        return [
            'httpRequest'     => [
                'parsers' => [
                    Request::CONTENT_JSON => bean(JsonRequestParser::class),
                    Request::CONTENT_XML  => bean(XmlRequestParser::class),
                ]
            ],
            'httpResponse'    => [
                'format'     => Response::FORMAT_JSON,
                'formatters' => [
                    Response::FORMAT_HTML => bean(HtmlResponseFormatter::class),
                    Response::FORMAT_JSON => bean(JsonResponseFormatter::class),
                    Response::FORMAT_XML  => bean(XmlResponseFormatter::class),
                ]
            ],
            'acceptFormatter' => [
                'formats' => [
                    Response::CONTENT_HTML => Response::FORMAT_HTML,
                    Response::CONTENT_JSON => Response::FORMAT_JSON,
                    Response::CONTENT_XML  => Response::FORMAT_XML,
                ]
            ],
            'httpServer'      => [
                'on' => [
                    SwooleEvent::REQUEST => bean(RequestListener::class)
                ]
            ],
            'httpRouter'      => [
                'name'            => 'swoft-http-router',
                // config
                'ignoreLastSlash' => true,
                'tmpCacheNumber'  => 500,
            ],
        ];
    }
}