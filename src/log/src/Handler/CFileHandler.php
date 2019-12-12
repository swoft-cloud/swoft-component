<?php declare(strict_types=1);


namespace Swoft\Log\Handler;

use InvalidArgumentException;
use Swoft\Bean\BeanFactory;
use Swoft\Log\Helper\Log;
use Swoft\Log\Logger;

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
        if ($this->logFile[0] === '@') {
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
        $messageText = implode("\n", $records) . "\n";

        $logFile = $this->formatFile($this->logFile);

        // Not all console log in coroutine
        $res = file_put_contents($logFile, $messageText, FILE_APPEND);

        if ($res === false) {
            throw new InvalidArgumentException(
                sprintf('Unable to append to log file: %s', $logFile)
            );
        }
    }

    /**
     * @param string $levels
     */
    public function setLevels(string $levels): void
    {
        $levelNames        = explode(',', $levels);
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
