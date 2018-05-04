<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Redis\Helper;

use Swoft\Redis\Exception\RedisException;

/**
 * Class RedisHelper
 * @package Swoft\Redis\Helper
 */
class RedisHelper
{
    /**
     * Parse uri
     *
     * @param string $uri `tcp://127.0.0.1:6379/1?auth=password`
     *
     * @return array
     * @throws RedisException
     */
    public static function redisParseUri(string $uri):array
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port'])) {
            $error = sprintf('Redis Connection format is incorrect uri=%s, eg:tcp://127.0.0.1:6379/1?auth=password', $uri);
            throw new RedisException($error);
        }
        isset($parseAry['path']) && $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query']?? '';
        parse_str($query, $options);
        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);

        return $configs;
    }
}
