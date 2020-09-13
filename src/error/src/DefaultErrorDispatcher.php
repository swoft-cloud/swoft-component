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

use ErrorException;
use InvalidArgumentException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\Contract\DefaultErrorHandlerInterface;
use Throwable;
use function error_get_last;
use function register_shutdown_function;
use function set_error_handler;
use function set_exception_handler;
use const E_ALL;
use const E_STRICT;

/**
 * Class DefaultErrorDispatcher
 *
 * @since 2.0
 * @Bean()
 */
class DefaultErrorDispatcher
{
    /**
     * @var int
     */
    private $errorTypes = E_ALL | E_STRICT;

    /**
     * @var DefaultErrorHandlerInterface
     */
    private $defaultHandler;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Add default handler
        $this->defaultHandler = new DefaultExceptionHandler();
    }

    public function init(): void
    {
        // Register system error handle
        $this->registerErrorHandle();
    }

    /**
     * Register system error handle
     *
     * @throws InvalidArgumentException
     */
    protected function registerErrorHandle(): void
    {
        set_error_handler([$this, 'handleError'], $this->errorTypes);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function(function (): void {
            if (!$e = error_get_last()) {
                return;
            }

            $this->handleError($e['type'], $e['message'], $e['file'], $e['line']);
        });
    }

    /**
     * Run error handling
     *
     * @param int    $num
     * @param string $str
     * @param string $file
     * @param int    $line
     *
     * @throws InvalidArgumentException
     * @throws ErrorException
     */
    public function handleError(int $num, string $str, string $file, int $line): void
    {
        // $this->handleException(new \ErrorException($str, 0, $num, $file, $line));
        throw new ErrorException($str, 0, $num, $file, $line);
    }

    /**
     * Running exception handling
     *
     * @param Throwable $e
     */
    public function handleException(Throwable $e): void
    {
        $this->defaultHandler->handle($e);
    }

    /**
     * @param Throwable $e
     */
    public function run(Throwable $e): void
    {
        $this->defaultHandler->handle($e);
    }

    /**
     * @return int
     */
    public function getErrorTypes(): int
    {
        return $this->errorTypes;
    }

    /**
     * @param int $errorTypes
     */
    public function setErrorTypes(int $errorTypes): void
    {
        $this->errorTypes = $errorTypes;
    }

    /**
     * @return DefaultErrorHandlerInterface
     */
    public function getDefaultHandler(): DefaultErrorHandlerInterface
    {
        return $this->defaultHandler;
    }

    /**
     * @param DefaultErrorHandlerInterface $defaultHandler
     */
    public function setDefaultHandler(DefaultErrorHandlerInterface $defaultHandler): void
    {
        $this->defaultHandler = $defaultHandler;
    }
}
