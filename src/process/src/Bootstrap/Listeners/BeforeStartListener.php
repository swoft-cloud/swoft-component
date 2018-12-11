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

namespace Swoft\Process\Bootstrap\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\BeforeStart;
use Swoft\Bootstrap\Listeners\Interfaces\BeforeStartInterface;
use Swoft\Bootstrap\Server\AbstractServer;
use Swoft\Process\Bean\Collector\ProcessCollector;
use Swoft\Process\ProcessBuilder;

/**
 * the listener of before server start
 *
 * @BeforeStart
 */
class BeforeStartListener implements BeforeStartInterface
{
    /**
     * @param AbstractServer $server
     * @throws \InvalidArgumentException
     * @throws \Swoft\Process\Exception\ProcessException
     */
    public function onBeforeStart(AbstractServer $server)
    {
        // add process
        $this->addProcess($server);
    }

    /**
     * @param AbstractServer $server
     * @throws \InvalidArgumentException
     * @throws \Swoft\Process\Exception\ProcessException
     */
    private function addProcess(AbstractServer $server)
    {
        $processes = ProcessCollector::getCollector();

        foreach ($processes as $beanName => $processInfo) {
            $num  = $processInfo['num'];
            $name = $processInfo['name'];
            $boot = $processInfo['boot'];

            $processObject = App::getBean($name);

            if (!$boot || !$processObject->check()) {
                continue;
            }

            while ($num > 0) {
                $num--;
                $process = ProcessBuilder::create($name);
                if ($process === null) {
                    continue;
                }

                $server->getServer()->addProcess($process->getProcess());
            }
        }
    }
}
