<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Helper\ErrorCode;
use Swoft\Auth\Mapping\AuthHandlerInterface;
use Swoft\Auth\Mapping\AuthorizationParserInterface;
use Swoft\Auth\Parser\Handler\BasicAuthHandler;
use Swoft\Auth\Parser\Handler\BearerTokenHandler;
use Swoft\Bean\Annotation\Value;
use Swoft\Helper\ArrayHelper;

class AuthorizationHeaderParser implements AuthorizationParserInterface
{
    /**
     * The parsers
     *
     * @var array
     */
    private $authTypes = [];

    /**
     * @Value("${config.auth.types}")
     * @var array
     */
    private $types = [];

    private $headerKey = AuthConstants::HEADER_KEY;

    /**
     * @throws AuthException When AuthHandler missing or error.
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $authValue = $request->getHeaderLine($this->headerKey);
        $type = $this->getHeadString($authValue);
        if (isset($this->mergeTypes()[$type])) {
            $handler = App::getBean($this->mergeTypes()[$type]);
            if (! $handler instanceof AuthHandlerInterface) {
                throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED, sprintf('%s  should implement Swoft\Auth\Mapping\AuthHandlerInterface', $this->mergeTypes()[$type]));
            }
            $request = $handler->handle($request);
        }
        return $request;
    }

    private function getHeadString(string $val): string
    {
        return explode(' ', $val)[0] ?? '';
    }

    private function mergeTypes(): array
    {
        if (empty($this->authTypes)) {
            $this->authTypes = ArrayHelper::merge($this->types, $this->defaultTypes());
        }
        return $this->authTypes;
    }

    public function defaultTypes(): array
    {
        return [
            BearerTokenHandler::NAME => BearerTokenHandler::class,
            BasicAuthHandler::NAME => BasicAuthHandler::class
        ];
    }
}
