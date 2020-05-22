<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Concern;

use Swoft\Contract\SessionInterface;
use Swoft\Contract\SessionStorageInterface;
use Swoft\Exception\SessionException;
use Swoft\Session\ArrayStorage;
use Swoft\Session\Session;
use function gethostname;

/**
 * Class AbstractSessionManager session connection manager
 *
 * @since 2.0.8
 */
abstract class AbstractSessionManager
{
    /**
     * The session storage handler
     *
     * @var SessionStorageInterface
     */
    private $storage;

    /**
     * @var string
     */
    protected $prefix = 'ss';

    /**
     * Bean init
     */
    public function init(): void
    {
        // Ensure the this->storage property is not empty.
        if (!$this->storage) {
            $this->storage = new ArrayStorage();
        }
    }

    /**
     * @param string $sessionId
     *
     * @return string eg: "wsMyHost:23"
     */
    public function genKey(string $sessionId): string
    {
        $hostname = gethostname();

        return $this->prefix . $hostname . ':' . $sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function has(string $sessionId): bool
    {
        if (Session::has($sessionId)) {
            return true;
        }

        $sessKey = $this->genKey($sessionId);

        return $this->storage->exists($sessKey);
    }

    /**
     * @param string           $sessionId The session Id. eg: swoole.fd, http session id
     * @param SessionInterface $session
     *
     * @return bool
     */
    public function set(string $sessionId, SessionInterface $session): bool
    {
        // Cache to Session::class
        Session::set($sessionId, $session);

        $sessKey = $this->genKey($sessionId);

        return $this->storage->write($sessKey, $session->toString());
    }

    /**
     * Get connection session object by sessionId.
     *
     * - If not found on current worker, will try find from storage driver.
     *
     * @param string $sessionId The session Id. eg: swoole.fd, http session id
     *
     * @return SessionInterface|null
     */
    public function get(string $sessionId): ?SessionInterface
    {
        // Read from caches
        if ($sess = Session::get($sessionId)) {
            return $sess;
        }

        // Read from storage
        $sessionKey  = $this->genKey($sessionId);
        $sessionData = $this->storage->read($sessionKey);

        if ($sessionData) {
            $sess = $this->restoreSession($sessionData);

            // Cache to memory
            Session::set($sessionId, $sess);
            return $sess;
        }

        return null;
    }

    /**
     * @param string $sessionData
     *
     * @return SessionInterface
     */
    abstract protected function restoreSession(string $sessionData): SessionInterface;

    /**
     * @return SessionInterface
     */
    public function current(): SessionInterface
    {
        $sessionId = Session::getBoundedSid();

        return $this->mustGet($sessionId);
    }

    /**
     * @param string $sessionId
     *
     * @return SessionInterface
     */
    public function mustGet(string $sessionId): SessionInterface
    {
        if ($session = $this->get($sessionId)) {
            return $session;
        }

        // throw new UnexpectedValueException('The session is not exists! sessionId is ' . $sessionId);
        throw new SessionException('session information has been lost of the SID: ' . $sessionId);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy(string $sessionId): bool
    {
        Session::destroy($sessionId);

        $sessKey = $this->genKey($sessionId);

        return $this->storage->destroy($sessKey);
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return $this->storage->clear();
    }

    /**
     * @return SessionStorageInterface
     */
    public function getStorage(): SessionStorageInterface
    {
        return $this->storage;
    }

    /**
     * @param SessionStorageInterface $storage
     */
    public function setStorage(SessionStorageInterface $storage): void
    {
        $this->storage = $storage;
    }
}
