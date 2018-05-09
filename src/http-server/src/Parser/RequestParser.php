<?php

namespace Swoft\Http\Server\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Helper\ArrayHelper;

/**
 * The parser of request
 */
class RequestParser implements RequestParserInterface
{
    /**
     * The parsers
     *
     * @var array
     */
    protected $parsers = [];

    /**
     * The of header
     *
     * @var string
     */
    protected $headerKey = 'Content-type';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $contentType = $request->getHeaderLine($this->headerKey);
        $parsers = $this->mergeParsers();

        if (!isset($parsers[$contentType])) {
            return $request;
        }

        /* @var \Swoft\Http\Server\Parser\RequestParserInterface $parser */
        $parserBeanName = $parsers[$contentType];
        $parser = App::getBean($parserBeanName);

        return $parser->parse($request);
    }

    /**
     * Merge default and users parsers
     *
     * @return array
     */
    protected function mergeParsers(): array
    {
        return ArrayHelper::merge($this->parsers, $this->defaultParsers());
    }

    /**
     * Default parsers
     *
     * @return array
     */
    public function defaultParsers(): array
    {
        return [
            'application/json' => RequestJsonParser::class,
            'application/xml' => RequestXmlParser::class,
        ];
    }
}
