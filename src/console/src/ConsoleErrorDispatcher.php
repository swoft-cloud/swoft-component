<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Contract\ConsoleErrorHandlerInterface;
use Swoft\Console\Exception\ConsoleErrorException;
use Swoft\Console\Helper\Show;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Throwable;
use function output;

/**
 * Class ConsoleErrorDispatcher
 *
 * @since 2.0.3
 * @Bean()
 */
class ConsoleErrorDispatcher
{
    /**
     * @param Throwable $e
     */
    public function run(Throwable $e): void
    {
        /** @var ErrorManager $manager */
        $manager = Swoft::getSingleton(ErrorManager::class);

        /** @var ConsoleErrorHandlerInterface $handler */
        if ($handler = $manager->matchHandler($e, ErrorType::CLI)) {
            $handler->handle($e);
            return;
        }

        // Console tips error
        if ($e instanceof ConsoleErrorException) {
            Show::error($e->getMessage());
            return;
        }

        // Ensure no buffer
        output()->clearBuffer();
        output()->flush();
        output()->writef(
            "<error>(CONSOLE)%s: %s</error>\nAt %s line <cyan>%d</cyan>\n<comment>Code Trace:</comment>\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }
}
