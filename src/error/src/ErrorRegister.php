<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Error;

use Swoft;
use Swoft\Error\Contract\ErrorHandlerInterface;
use function count;

/**
 * Class ErrorRegister
 *
 * @since 2.0
 */
final class ErrorRegister
{
    /**
     * @var array
     * [
     *  handler class => [exception class, exception class1],
     * ]
     */
    private static $handlers = [];

    /**
     * @param string $handlerClass
     * @param array  $exceptions
     */
    public static function add(string $handlerClass, array $exceptions): void
    {
        self::$handlers[$handlerClass] = $exceptions;
    }

    /**
     * @param ErrorManager $manager
     *
     * @return int
     */
    public static function register(ErrorManager $manager): int
    {
        foreach (self::$handlers as $handlerClass => $exceptions) {
            /** @var ErrorHandlerInterface $handler */
            $handler = Swoft::getSingleton($handlerClass);
            $typeVal = $handler->getType();

            foreach ($exceptions as $exceptionClass) {
                $manager->addHandler($exceptionClass, $handlerClass, $typeVal);
            }
        }

        $count = count(self::$handlers);

        // Clear handlers
        self::$handlers = [];

        return $count;
    }
}
