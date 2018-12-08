<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\ErrorHandler\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bootstrap\Boots\Bootable;
use Swoft\ErrorHandler\Bean\Annotation;
use Swoft\ErrorHandler\Bean\Collector;
use Swoft\ErrorHandler\Bean\Parser;
use Swoft\ErrorHandler\Bean\Wrapper;

/**
 * Namespace compatibility with previous versions, which non-componentization version
 * @Bootstrap(order=1)
 */
class CompPreviousVersionNamespace implements Bootable
{
    /**
     * @return void
     */
    public function bootstrap()
    {
        $map = [
            Wrapper\ExceptionHandlerWrapper::class => 'Swoft\Bean\Wrapper\ExceptionHandlerWrapper',
            Parser\ExceptionHandlerParser::class => 'Swoft\Bean\Parser\ExceptionHandlerParser',
            Parser\HandlerParser::class => 'Swoft\Bean\Parser\HandlerParser',
            Collector\ExceptionHandlerCollector::class => 'Swoft\Bean\Collector\ExceptionHandlerCollector',
            Annotation\ExceptionHandler::class => 'Swoft\Bean\Annotation\ExceptionHandler',
            Annotation\Handler::class => 'Swoft\Bean\Annotation\Handler',
        ];
        foreach ($map as $original => $alias) {
            \class_alias($original, $alias, true);
        }
    }
}
