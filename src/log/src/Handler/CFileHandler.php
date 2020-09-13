<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Log\Handler;

use InvalidArgumentException;
use Swoft\Bean\BeanFactory;
use Swoft\Log\Helper\Log;
use Swoft\Log\Logger;
use function array_column;
use function array_map;
use function file_put_contents;
use function implode;
use const FILE_APPEND;

/**
 * Class CFileHandler
 *
 * @since 2.0
 */
class CFileHandler extends FileHandler
{
    /**
     * booting console log
     *
     * @var array
     */
    private $bootingRecords = [];

    /**
     * Write console log to file
     *
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (empty($this->logFile)) {
            return;
        }

        // Not boot bean
        if (false === BeanFactory::hasBean('logger')) {
            $this->bootingRecords[] = $record;
            return;
        }

        $records = [$record];

        // Not init
        if (strpos($this->logFile, '@') === 0) {
            $this->init();

            $this->bootingRecords[] = $record;

            $records = $this->bootingRecords;

            unset($this->bootingRecords);
        }

        if (Log::getLogger()->isJson()) {
            $records = array_map([$this, 'formatJson'], $records);
        } else {
            $records = array_column($records, 'formatted');
        }

        $message = implode("\n", $records) . "\n";
        $logFile = $this->formatFile($this->logFile);

        // Not all console log in coroutine
        $count = file_put_contents($logFile, $message, FILE_APPEND);
        if ($count === false) {
            throw new InvalidArgumentException(sprintf('Unable to append to log file: %s', $logFile));
        }
    }

    /**
     * @param string $levels
     */
    public function setLevels(string $levels): void
    {
        $levelNames = explode(',', $levels);

        $this->levelValues = Logger::getLevelByNames($levelNames);

        $this->levels = $levels;
    }

    /**
     * @param string $logFile
     */
    public function setLogFile(string $logFile): void
    {
        $this->logFile = $logFile;
    }
}
