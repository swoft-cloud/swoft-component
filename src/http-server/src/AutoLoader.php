<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server;

use Swoft\Helper\ComposerJSON;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Formatter\HtmlResponseFormatter;
use Swoft\Http\Server\Formatter\JsonResponseFormatter;
use Swoft\Http\Server\Formatter\XmlResponseFormatter;
use Swoft\Http\Server\Parser\JsonRequestParser;
use Swoft\Http\Server\Parser\XmlRequestParser;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Server\SwooleEvent;
use Swoft\SwoftComponent;
use function bean;
use function dirname;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

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
     */
    public function beans(): array
    {
        return [
            'httpRequest'     => [
                'parsers' => [
                    ContentType::XML  => bean(XmlRequestParser::class),
                    ContentType::JSON => bean(JsonRequestParser::class),
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
                    ContentType::JSON => Response::FORMAT_JSON,
                    ContentType::HTML => Response::FORMAT_HTML,
                    ContentType::XML  => Response::FORMAT_XML,
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
