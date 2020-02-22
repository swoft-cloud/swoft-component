<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Context;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\Request;

/**
 * Class WsOpenContext - on ws open event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsOpenContext extends AbstractContext
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     *
     * @return WsOpenContext
     */
    public static function new(Request $request): self
    {
        /** @var self $ctx */
        $ctx = Swoft::getBean(self::class);

        // Initial properties
        $ctx->request = $request;

        return $ctx;
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        parent::clear();

        $this->request = null;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}
