<?php

namespace Swoft\Session\Handler;

use Carbon\Carbon;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Session\Exception\RuntimeException;

/**
 * @Bean()
 * Class  FileSessionHandler
 * @author    huangzhhui <huangzhwork@gmail.com>
 */
class FileSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $minutes;

    /**
     * FileSessionHandler constructor.
     *
     * @param string $path
     * @param int $minutes
     * @throws \InvalidArgumentException
     */
    public function __construct($path = null, $minutes = 15)
    {
        if (null !== $path) {
            $this->path = $path;
        } else {
            $this->path = App::getAlias('@runtime/sessions');
        }
        $this->minutes = $minutes;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function destroy($session_id)
    {
        // TODO
        return true;
    }

    /**
     * @inheritdoc
     */
    public function gc($maxLifetime)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function read($sessionId)
    {
        $path = $this->path . '/' . $sessionId;
        if (file_exists($path)) {
            if (filemtime($path) >= Carbon::now()->subMinutes($this->minutes)->getTimestamp()) {
                return file_get_contents($path);
            }
        }

        return '';
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     */
    public function write($sessionId, $data): bool
    {
        if (! file_exists($this->path) && ! mkdir($this->path, 0755) && ! is_dir($this->path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->path));
        }
        $path = $this->path . '/' . $sessionId;

        file_put_contents($path, $data);

        return true;
    }

}
