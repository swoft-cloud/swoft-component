<?php

namespace Swoft\Http\Server;

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
                    JsonResponseFormatter::CONTENT_TYPE => bean(JsonResponseFormatter::class),
                    XmlResponseFormatter::CONTENT_TYPE  => bean(XmlResponseFormatter::class),
                ]
            ],
            'acceptFormatter' => [
                'formats' => [
                    'json' => Request::CONTENT_JSON,
                    'xml'  => Request::CONTENT_XML,
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