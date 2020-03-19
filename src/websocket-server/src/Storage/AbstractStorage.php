<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Storage;

/**
 * Class AbstractStorage
 */
abstract class AbstractStorage
{
    /**
     * server ID
     *
     * @var string
     */
    protected $id;

    public $namespace = '/';

    /**
     * @var array
     */
    private $namespaces = [
        '/' => 'rooms'
    ];

    /*****************************************************************************
     * some methods for rooms(of an namespace)
     ****************************************************************************/

    /**
     * rooms cache
     *
     * @var array
     */
    private $rooms = [];

    /**
     * @param string $name
     */
    public function newRoom(string $name): void
    {
    }

    /**
     * @param int    $fd
     * @param string $name
     */
    public function joinRoom(int $fd, string $name): void
    {
    }

    /**
     * @param int    $fd
     * @param string $name
     */
    public function leaveRoom(int $fd, string $name): void
    {
    }
}
