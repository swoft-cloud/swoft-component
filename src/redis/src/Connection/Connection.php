<?php declare(strict_types=1);


namespace Swoft\Redis\Connection;


use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Redis\Contract\ConnectionInterface;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Pool;
use Swoft\Redis\RedisDb;

/**
 * Class Connection
 *
 * @since 2.0
 * @method int append(string $key, string $value)
 * @method array blPop(array $keys, int $timeout)
 * @method array brPop(array $keys, int $timeout)
 * @method string brpoplpush(string $srcKey, string $dstKey, int $timeout)
 * @method string decr(string $key)
 * @method int decrBy(string $key, int $value)
 * @method mixed eval(string $script, array $args = [], int $numKeys = 0)
 * @method mixed evalSha(string $scriptSha, array $args = [], int $numKeys = 0)
 * @method bool exists(string $key)
 * @method int geoAdd(string $key, float $longitude, float $latitude, string $member)
 * @method float geoDist(string $key, string $member1, string $member2, string $unit = 'm')
 * @method array geohash(string $key, string $member1, string $member2 = null, string $memberN = null)
 * @method array geopos(string $key, string $member1, string $member2 = null, string $memberN = null)
 * @method string|bool get(string $key)
 * @method int getBit(string $key, int $offset)
 * @method int getOption(string $name)
 * @method string getRange(string $key, int $start, int $end)
 * @method string getSet(string $key, string $value)
 * @method string hDel(string $key, string $hashKey1, string $hashKey2 = null, string $hashKeyN = null)
 * @method bool hExists(string $key, string $hashKey)
 * @method array hGet(string $key, array $hashKey)
 * @method array hGetAll(string $key)
 * @method int hIncrBy(string $key, string $hashKey, int $value)
 * @method float hIncrByFloat(string $key, string $field, float $increment)
 * @method array hKeys(string $key)
 * @method int hLen(string $key)
 * @method int hSet(string $key, string $hashKey, string $value)
 * @method bool hSetNx(string $key, string $hashKey, string $value)
 * @method array hVals(string $key)
 * @method array hScan(string $key, int &$iterator, string $pattern = null, int $count = 0)
 * @method int incr(string $key)
 * @method int incrBy(string $key, int $value)
 * @method float incrByFloat(string $key, float $increment)
 * @method array info(string $option = null)
 * @method string|bool lGet(string $key, int $index)
 * @method int lInsert(string $key, int $position, string $pivot, string $value)
 * @method string|bool lPop(string $key)
 * @method int|bool lPush(string $key, string $value1, string $value2 = null, string $valueN = null)
 * @method int|bool lPushx(string $key, string $value)
 * @method bool lSet(string $key, int $index, string $value)
 * @method int msetnx(array $array)
 * @method bool persist(string $key)
 * @method bool pExpire(string $key, int $ttl)
 * @method bool pExpireAt(string $key, int $timestamp)
 * @method bool psetex(string $key, int $ttl, string $value)
 * @method int pttl(string $key)
 * @method string rPop(string $key)
 * @method int|bool rPush(string $key, string $value1, string $value2 = null, string $valueN = null)
 * @method int|bool rPushx(string $key, string $value)
 * @method mixed rawCommand(string|array $nodeParams, string $command, mixed $arguments)
 * @method bool renameNx(string $srcKey, string $dstKey)
 * @method bool restore(string $key, int $ttl, string $value)
 * @method string rpoplpush(string $srcKey, string $dstKey)
 * @method int sAdd(string $key, string $value1, string $value2 = null, string $valueN = null)
 * @method int sAddArray(string $key, array $valueArray)
 * @method array sDiff(string $key1, string $key2, string $keyN = null)
 * @method int sDiffStore(string $dstKey, string $key1, string $key2, string $keyN = null)
 * @method array sInter(string $key1, string $key2, string $keyN = null)
 * @method int|bool sInterStore(string $dstKey, string $key1, string $key2, string $keyN = null)
 * @method array sMembers(string $key)
 * @method bool sMove(string $srcKey, string $dstKey, string $member)
 * @method string|bool sPop(string $key)
 * @method string|array|bool sRandMember(string $key, int $count = null)
 * @method array sUnion(string $key1, string $key2, string $keyN = null)
 * @method int sUnionStore(string $dstKey, string $key1, string $key2, string $keyN = null)
 * @method array|bool scan(int &$iterator, string $pattern = null, int $count = 0)
 * @method mixed script(string|array $nodeParams, string $command, string $script)
 * @method bool set(string $key, string $value, int $timeout = null)
 * @method int setBit(string $key, int $offset, int $value)
 * @method string setRange(string $key, int $offset, string $value)
 * @method int setex(string $key, int $ttl, string $value)
 * @method bool setnx(string $key, string $value)
 * @method array sort(string $key, array $option = null)
 * @method array sScan(string $key, int &$iterator, string $pattern = null, int $count = 0)
 * @method int strlen(string $key)
 * @method int ttl(string $key)
 * @method int type(string $key)
 * @method void unwatch()
 * @method void watch(string $key)
 * @method int zCard(string $key)
 * @method int zCount(string $key, int $start, int $end)
 * @method float zIncrBy(string $key, float $value, string $member)
 * @method int zLexCount(string $key, int $min, int $max)
 * @method array zRange(string $key, int $start, int $end, bool $withscores = null)
 * @method array zRangeByLex(string $key, int $min, int $max, int $offset = null, int $limit = null)
 * @method array zRangeByScore(string $key, int $start, int $end, array $options = [])
 * @method int zRank(string $key, string $member)
 * @method array zRemRangeByLex(string $key, int $min, int $max)
 * @method array zRevRange(string $key, int $start, int $end, bool $withscore = null)
 * @method array zRevRangeByLex(string $key, int $min, int $max, int $offset = null, int $limit = null)
 * @method array zRevRangeByScore(string $key, int $start, int $end, array $options = [])
 * @method int zRevRank(string $key, string $member)
 * @method float zScore(string $key, float $member)
 * @method array zScan(string $key, int &$iterator, string $pattern = null, int $count = 0)
 * @method int del(string $key1, string $key2 = null, string $key3 = null)
 * @method bool expire(string $key, int $ttl)
 * @method array keys(string $pattern)
 * @method int lLen(string $key)
 * @method string|bool lIndex(string $key, int $index)
 * @method array lRange(string $key, int $start, int $end)
 * @method int|bool lRem(string $key, string $value, int $count)
 * @method array|bool lTrim(string $key, int $start, int $stop)
 * @method bool rename(string $srcKey, string $dstKey)
 * @method int sCard(string $key)
 * @method bool sIsMember(string $key, string $value)
 * @method int sRem(string $key, string $member1, string $member2 = null, string $memberN = null)
 * @method int zRem(string $key, string $member1, string $member2 = null, string $memberN = null)
 * @method int zRemRangeByRank(string $key, int $start, int $end)
 * @method int zRemRangeByScore(string $key, float|string $start, float|string $end)
 * @method int zInterStore(string $Output, array $ZSetKeys, array $Weights = null, string $aggregateFunction = 'SUM')
 * @method int zUnionStore(string $Output, array $ZSetKeys, array $Weights = null, string $aggregateFunction = 'SUM')
 */
abstract class Connection extends AbstractConnection implements ConnectionInterface
{
    /**
     * Supported methods
     *
     * @var array
     */
    protected $supportedMethods = [
        'append',
        'blpop',
        'brpop',
        'brpoplpush',
        'decr',
        'decrby',
        'eval',
        'evalsha',
        'exists',
        'geoadd',
        'geodist',
        'geohash',
        'geopos',
        'get',
        'getbit',
        'getoption',
        'getrange',
        'getset',
        'hdel',
        'hexists',
        'hget',
        'hgetall',
        'hincrby',
        'hincrbyfloat',
        'hkeys',
        'hlen',
        'hmget',
        'hmset',
        'hset',
        'hsetnx',
        'hvals',
        'hscan',
        'incr',
        'incrby',
        'incrbyfloat',
        'info',
        'lget',
        'linsert',
        'lpop',
        'lpush',
        'lpushx',
        'lset',
        'mset',
        'msetnx',
        'persist',
        'pexpire',
        'pexpireat',
        'psetex',
        'pttl',
        'rpop',
        'rpush',
        'rpushx',
        'rawcommand',
        'renamenx',
        'restore',
        'rpoplpush',
        'sadd',
        'saddarray',
        'sdiff',
        'sdiffstore',
        'sinter',
        'sinterstore',
        'smembers',
        'smove',
        'spop',
        'srandmember',
        'sunion',
        'sunionstore',
        'scan',
        'script',
        'set',
        'setbit',
        'setrange',
        'setex',
        'setnx',
        'sort',
        'sscan',
        'strlen',
        'ttl',
        'type',
        'unwatch',
        'watch',
        'zadd',
        'zcard',
        'zcount',
        'zincrby',
        'zlexcount',
        'zrange',
        'zrangebylex',
        'zrangebyscore',
        'zrank',
        'zremrangebylex',
        'zrevrange',
        'zrevrangebylex',
        'zrevrangebyscore',
        'zrevrank',
        'zscore',
        'zscan',
        'del',
        'expire',
        'keys',
        'llen',
        'lindex',
        'lrange',
        'lrem',
        'ltrim',
        'mget',
        'rename',
        'scard',
        'sismember',
        'srem',
        'zrem',
        'zremrangebyrank',
        'zremrangebyscore',
        'zinterstore',
        'zunionstore',

        'psubscribe',
        'multi',
        'publish',
        'pubsub',
        'punsubscribe',
        'subscribe',
        'unsubscribe',
    ];

    /**
     * @var \Redis|\RedisCluster
     */
    protected $client;

    /**
     * @var RedisDb
     */
    protected $redisDb;

    /**
     * @param Pool    $pool
     * @param RedisDb $redisDb
     */
    public function initialize(Pool $pool, RedisDb $redisDb)
    {
        $this->pool    = $pool;
        $this->redisDb = $redisDb;

        $this->id = $this->pool->getConnectionId();
    }

    /**
     * @throws RedisException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function create(): void
    {
        $clusters = $this->redisDb->getClusters();
        if (!empty($clusters)) {
            $this->createClusterClient();
            return;
        }

        $this->createClient();
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Redis\Exception\RedisException
     */
    public function createClient(): void
    {
        $config = [];
        $option = $this->redisDb->getOption();

        $this->client = $this->redisDb->getConnector()->connect($config, $option);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Redis\Exception\RedisException
     */
    public function createClusterClient(): void
    {
        $config = [];
        $option = $this->redisDb->getOption();

        $this->client = $this->redisDb->getConnector()->connectToCluster($config, $option);
    }

    /**
     * Run a command against the Redis database.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     * @throws RedisException
     */
    public function command(string $method, array $parameters = [])
    {
        $lowerMethod = $method;
        if (!in_array($lowerMethod, $this->supportedMethods, true)) {
            throw new RedisException(
                sprintf('Method(%s) is not supported!', $method)
            );
        }

        $result = $this->client->{$method}(...$parameters);
        return $result;
    }

    public function hMGet(string $key, array $keys): array
    {
        return [];
    }

    public function hMSet(string $key, array $keyValues): bool
    {
        // TODO: Implement hMSet() method.
    }

    public function zAdd(string $key, array $scoreValues): int
    {
        // TODO: Implement zAdd() method.
    }

    public function mget(array $keys): array
    {
        // TODO: Implement mget() method.
    }

    public function mset(array $keyValues): bool
    {
        // TODO: Implement mset() method.
    }


    public function reconnect(): bool
    {
        // TODO: Implement reconnect() method.
    }

    public function getLastTime(): int
    {
        // TODO: Implement getLastTime() method.
    }

    /**
     * Pass other method calls down to the underlying client.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     * @throws RedisException
     */
    public function __call(string $method, array $parameters)
    {
        return $this->command($method, $parameters);
    }

    /**
     * Apply prefix to the given key if necessary.
     *
     * @param  string $key
     *
     * @return string
     */
    private function applyPrefix(string $key): string
    {
        $prefix = (string)$this->client->getOption(\Redis::OPT_PREFIX);

        return $prefix . $key;
    }
}