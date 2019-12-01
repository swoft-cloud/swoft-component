<?php declare(strict_types=1);


namespace Swoft\Log\Handler;

use Swoft\Exception\SwoftException;
use Swoft\Log\Logger;

/**
 * Class CFileHandler
 *
 * @since 2.0
 */
class CFileHandler extends FileHandler
{
    /**
     * booting records
     *
     * @var array
     */
    private $bootingRecords = [];

    /**
     * Is boot
     *
     * @var bool
     */
    private $boot = false;

    /**
     * Write console log to file
     *
     * @param array $record
     *
     * @throws SwoftException
     */
    protected function write(array $record): void
    {
        if (empty($this->logFile)) {
            return;
        }

        // Logger no ready
        if (!$this->boot) {
            $this->bootingRecords[] = [$record];
            return;
        }

        parent::write([$record]);
    }

    /**
     * Init console file log
     *
     * @throws SwoftException
     */
    public function init(): void
    {
        if (empty($this->logFile)) {
            return;
        }

        if ($this->boot) {
            return;
        }
        parent::init();

        $this->boot = true;

        foreach ($this->bootingRecords as $records) {
            parent::write($records);
        }

        unset($this->bootingRecords);
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
