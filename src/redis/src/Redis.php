<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Redis;

use Psr\SimpleCache\CacheInterface;
use Swoft\App;
use Swoft\Cache\CacheCoResult;
use Swoft\Cache\CacheDataResult;
use Swoft\Core\ResultInterface;
use Swoft\Pool\ConnectionInterface;
use Swoft\Pool\PoolInterface;
use Swoft\Redis\Pool\RedisPool;

/**
 * Redis
 * key and string
 * @method int append($key, $value)
 * @method int decr($key)
 * @method int decrBy($key, $value)
 * @method string getRange($key, $start, $end)
 * @method int incr($key)
 * @method int incrBy($key, $value)
 * @method float incrByFloat($key, $increment)
 * @method int strlen($key)
 * @method bool mset(array $array)
 * @method int ttl($key)
 * @method int expire($key, $seconds)
 * @method int pttl($key)
 * @method int persist($key)
 * hash
 * @method int hSet($key, $hashKey, $value)
 * @method bool hSetNx($key, $hashKey, $value)
 * @method string hGet($key, $hashKey)
 * @method int hLen($key)
 * @method int hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null)
 * @method array hKeys($key)
 * @method array hVals($key)
 * @method array hGetAll($key)
 * @method bool hExists($key, $hashKey)
 * @method bool hIncrBy($key, $hashKey, $value)
 * @method bool hIncrByFloat($key, $field, $increment)
 * @method bool hMset($key, $hashKeys)
 * @method array hMGet($key, $hashKeys)
 * list
 * @method array brPop(array $keys, $timeout)
 * @method array blPop(array $keys, $timeout)
 * @method int lLen($key)
 * @method int lPush($key, $value1, $value2 = null, $valueN = null)
 * @method string lPop($key)
 * @method array lRange($key, $start, $end)
 * @method int lRem($key, $value, $count)
 * @method bool lSet($key, $index, $value)
 * @method int rPush($key, $value1, $value2 = null, $valueN = null)
 * @method string rPop($key)
 * set
 * @method int sAdd($key, $value1, $value2 = null, $valueN = null)
 * @method array|bool scan(&$iterator, $pattern = null, $count = 0)
 * @method int sCard($key)
 * @method array sDiff($key1, $key2, $keyN = null)
 * @method array sInter($key1, $key2, $keyN = null)
 * @method int sInterStore($dstKey, $key1, $key2, $keyN = null)
 * @method int sDiffStore($dstKey, $key1, $key2, $keyN = null)
 * @method array sMembers($key)
 * @method bool sMove($srcKey, $dstKey, $member)
 * @method bool sPop($key)
 * @method string|array sRandMember($key, $count = null)
 * @method int sRem($key, $member1, $member2 = null, $memberN = null)
 * @method array sUnion($key1, $key2, $keyN = null)
 * @method int sUnionStore($dstKey, $key1, $key2, $keyN = null)
 * sort
 * @method int zAdd($key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null)
 * @method array zRange($key, $start, $end, $withscores = null)
 * @method int zRem($key, $member1, $member2 = null, $memberN = null)
 * @method array zRevRange($key, $start, $end, $withscore = null)
 * @method array zRangeByScore($key, $start, $end, array $options = array())
 * @method array zRangeByLex($key, $min, $max, $offset = null, $limit = null)
 * @method int zCount($key, $start, $end)
 * @method int zRemRangeByScore($key, $start, $end)
 * @method int zRemRangeByRank($key, $start, $end)
 * @method int zCard($key)
 * @method float zScore($key, $member)
 * @method int zRank($key, $member)
 * @method float zIncrBy($key, $value, $member)
 * @method int zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM')
 * @method int zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM')
 * pub/sub
 * @method int publish($channel, $message)
 * @method string|array psubscribe($patterns, $callback)
 * @method string|array subscribe($channels, $callback)
 * @method array|int pubsub($keyword, $argument)
 * script
 * @method mixed eval($script, $args = array(), $numKeys = 0)
 * @method mixed evalSha($scriptSha, $args = array(), $numKeys = 0)
 * @method mixed script($command, $script)
 * @method string getLastError()
 * @method bool clearLastError()
 */
class Redis implements CacheInterface
{
    /**
     * @var string
     */
    private $poolName = RedisPool::class;

    /**
     * Get the value related to the specified key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|bool
     */
    public function get($key, $default = null)
    {
        $result = $this->call('get', [$key]);
        if ($result === false || $result === null) {
            return $default;
        }

        return $result;
    }

    /**
     * Set the string value in argument as value of the key.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        $ttl    = $this->getTtl($ttl);
        $params = ($ttl === 0) ? [$key, $value] : [$key, $value, $ttl];

        return $this->call('set', $params);
    }

    /**
     * Remove specified keys.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete($key): bool
    {
        return (bool)$this->call('del', [$key]);
    }

    /**
     * Removes all entries from the current database.
     *
     * @return  bool  Always TRUE.
     */
    public function clear(): bool
    {
        return $this->call('flushDB', []);
    }

    /**
     * Returns the values of all specified keys.
     * For every key that does not hold a string value or does not exist,
     * the special value false is returned. Because of this, the operation never fails.
     *
     * @param iterable $keys
     * @param mixed    $default
     *
     * @return array|mixed
     */
    public function getMultiple($keys, $default = null)
    {
        $mgetResult = $this->call('mget', [$keys]);
        if ($mgetResult === false) {
            return $default;
        }
        $result = [];
        foreach ($mgetResult ?? [] as $key => $value) {
            $result[$keys[$key]] = $value;
        }

        return $result;
    }

    /**
     * Sets multiple key-value pairs in one atomic command.
     *
     * @param iterable $values
     * @param int      $ttl
     *
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $result = $this->call('mset', [$values]);

        return $result;
    }

    /**
     * Remove specified keys.
     *
     * @param iterable $keys
     *
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        return (bool)$this->call('del', [$keys]);
    }

    /**
     * Verify if the specified key exists.
     *
     * @param string $key
     *
     * @return  bool  If the key exists, return TRUE, otherwise return FALSE.
     */
    public function has($key): bool
    {
        return $this->call('exists', [$key]);
    }

    /**
     * defer call
     *
     * @param string $method
     * @param array  $params
     *
     * @return ResultInterface
     */
    public function deferCall(string $method, array $params)
    {
        $connectPool = App::getPool($this->poolName);

        /* @var $client RedisConnection */
        $client = $connectPool->getConnection();
        $client->setDefer();
        $result = $client->$method(...$params);

        return $this->getResult($client, $result);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function sIsMember($key, $value): bool
    {
        return (bool)$this->call('sIsMember', [$key, $value]);
    }

    /**
     * @param array $keys
     *
     * @return array|mixed
     */
    public function mget(array $keys)
    {
        return $this->getMultiple($keys, false);
    }

    /**
     * magic method
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->call($method, $arguments);
    }

    /**
     * call method by redis client
     *
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    private function call(string $method, array $params)
    {
        /* @var PoolInterface $connectPool */
        $connectPool = App::getPool($this->poolName);
        /* @var ConnectionInterface $client */
        $connection = $connectPool->getConnection();
        $result     = $connection->$method(...$params);
        $connection->release(true);

        return $result;
    }

    /**
     * @param ConnectionInterface $connection
     * @param mixed               $result
     *
     * @return ResultInterface
     */
    private function getResult(ConnectionInterface $connection, $result)
    {
        if (App::isCoContext()) {
            return new CacheCoResult($result, $connection);
        }

        return new CacheDataResult($result, $connection);
    }

    /**
     * the ttl
     *
     * @param $ttl
     *
     * @return int
     */
    private function getTtl($ttl): int
    {
        return ($ttl === null) ? 0 : (int)$ttl;
    }
}
