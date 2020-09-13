<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Error;
use Swoft\Process\Contract\UserProcessInterface;
use Swoft\Process\Process;
use Swoft\Process\ProcessEvent;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use Swoft\Server\ServerEvent;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Process as SwooleProcess;
use Throwable;

/**
 * Class AddProcessListener
 *
 * @since 2.0
 *
 * @Listener(event=ServerEvent::ADDED_PROCESS)
 */
class AddProcessListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws ServerException
     */
    public function handle(EventInterface $event): void
    {
        /* @var Server $server */
        $server = $event->getTarget();

        $this->addProcesses($server);
    }

    /**
     * Add process
     *
     * @param Server $server
     *
     * @throws ServerException
     */
    private function addProcesses(Server $server): void
    {
        $process = $server->getProcess();
        if (empty($process)) {
            return;
        }

        foreach ($process as $name => $userProcess) {
            if (!$userProcess instanceof UserProcessInterface) {
                throw new ServerException('Server add process must be instanceof UserProcessInterface!');
            }

            $callback  = [$userProcess, 'run'];
            $stdinOut  = $userProcess->isStdinOut();
            $pipeType  = $userProcess->getPipeType();
            $coroutine = $userProcess->isCoroutine();

            $function = function (SwooleProcess $swProcess) use ($callback, $server, $name) {
                $process = Process::new($swProcess);

                try {
                    // Before
                    Swoft::trigger(ProcessEvent::BEFORE_USER_PROCESS, null, $server, $process, $name);

                    // Run
                    // TODO use $userProcess->run($process);
                    PhpHelper::call($callback, $process);

                    // After
                    Swoft::trigger(ProcessEvent::AFTER_USER_PROCESS);
                } catch (Throwable $e) {
                    Error::log('User process fail(%s %s %d)!', $e->getFile(), $e->getMessage(), $e->getLine());
                }
            };

            $process = new SwooleProcess($function, $stdinOut, $pipeType, $coroutine);

            // save swoole process
            $userProcess->setSwooleProcess($process);
            $server->getSwooleServer()->addProcess($process);
        }
    }
}
