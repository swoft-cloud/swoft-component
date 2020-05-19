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

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\HttpErrorHandlerInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\PhpHelper;
use Throwable;
use const APP_DEBUG;

/**
 * Class HttpErrorHandler
 *
 * @since 2.0
 *
 * @Bean()
 */
class HttpErrorDispatcher
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     */
    public function run(Throwable $e, Response $response): Response
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var HttpErrorHandlerInterface $handler */
        if ($handler = $handlers->match($e, ErrorType::HTTP)) {
            return $handler->handle($e, $response);
        }

        // Print log to console
        CLog::error(PhpHelper::exceptionToString($e, 'Http Error', APP_DEBUG > 0));

        return $response->withStatus(500)->withContent($e->getMessage());
    }
}
