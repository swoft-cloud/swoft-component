<?php declare(strict_types=1);

namespace Swoft\Session;

use InvalidArgumentException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Contract\SessionStorageInterface;
use Swoft\Exception\SessionException;
use Swoft\Stdlib\Helper\JsonHelper;
use function class_exists;
use function count;
use function is_subclass_of;

/**
 * Class SessionManager
 *
 * @Bean("gSessionManager")
 */
class SessionManager
{
    /**
     * Cached session instance list
     *
     * @var SessionInterface[]
     */
    private $caches = [];

    /**
     * The session storage bean
     *
     * @var SessionStorageInterface
     */
    private $storage;

    /**
     * The session bean class name
     *
     * @var string
     */
    private $sessionClass;

    /**
     * Bean init
     */
    public function init(): void
    {
        // Ensure the this->storage property is not empty.
        if (!$this->storage) {
            $this->storage = new ArrayStorage();
            // $this->arrayStorage = true;
        }
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function has(string $sessionId): bool
    {
        if (isset($this->caches[$sessionId])) {
            return true;
        }

        return $this->storage->exists($sessionId);
    }

    /**
     * @param string           $sessionId The session Id. eg: swoole.fd, http session id
     * @param SessionInterface $session
     *
     * @return bool
     */
    public function set(string $sessionId, SessionInterface $session): bool
    {
        return $this->getStorage()->write($sessionId, $session->toString());
    }

    /**
     * @param string $sessionId The session Id. eg: swoole.fd, http session id
     *
     * @return SessionInterface|null
     */
    public function get(string $sessionId): ?SessionInterface
    {
        // Read from caches
        if (isset($this->caches[$sessionId])) {
            return $this->caches[$sessionId];
        }

        // Read from storage
        $sessionData = $this->getStorage()->read($sessionId);

        if ($sessionData) {
            /** @var SessionInterface $class */
            $class = $this->sessionClass;
            $data  = JsonHelper::decode($sessionData, true);
            $sess  = $class::newFromArray($data);

            $this->caches[$sessionId] = $sess;
            return $sess;
        }

        return null;
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
        if (isset($this->caches[$sessionId])) {
            // Clear self data
            $this->caches[$sessionId]->clear();
            unset($this->caches[$sessionId]);
        }

        return $this->storage->destroy($sessionId);
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        $this->caches = [];

        return $this->storage->clear();
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function hasCache(string $sessionId): bool
    {
        return isset($this->caches[$sessionId]);
    }

    /**
     * @return bool
     */
    public function clearCaches(): bool
    {
        $this->caches = [];
        return true;
    }

    /**
     * @return int
     */
    public function countCaches(): int
    {
        return count($this->caches);
    }

    /**
     * @return SessionInterface[]
     */
    public function getCaches(): array
    {
        return $this->caches;
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

    /**
     * @return string
     */
    public function getSessionClass(): string
    {
        return $this->sessionClass;
    }

    /**
     * @param string $sessionClass
     */
    public function setSessionClass(string $sessionClass): void
    {
        if (!$sessionClass) {
            return;
        }

        // Check class
        if (!class_exists($sessionClass) || !is_subclass_of($sessionClass, SessionInterface::class)) {
            throw new InvalidArgumentException('The session class must be implemented ' . SessionInterface::class);
        }

        $this->sessionClass = $sessionClass;
    }
}
