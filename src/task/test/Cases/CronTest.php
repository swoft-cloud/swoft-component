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

namespace SwoftTest\Task\Cases;

use Swoft\Task\Bean\Collector\TaskCollector;
use Swoft\Task\Crontab\ParseCrontab;

class CronTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function cronSyntax()
    {
        $expect = [
            '* * * * * *',
            '3-5 * * * * *',
            '*\/3 * * * * *'
        ];
        $collector = TaskCollector::getCollector();
        $scheduleds = array_column($collector['crons'], 'cron');
        foreach ($scheduleds as $scheduled) {
            $this->assertContains($scheduled, $expect);
            $result = ParseCrontab::parse($scheduled);
            $isArray = is_array($result);
            $this->assertTrue($isArray);
        }
    }
}
