<?php declare(strict_types=1);


namespace SwoftTest\Rpc\Server\Testing\Lib;

/**
 * Class DemoInterface
 *
 * @since 2.0
 */
interface DemoInterface
{
    /**
     * @param int    $uid
     * @param string $type
     *
     * @return array
     */
    public function getList(int $uid, string $type): array;

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function getInfo($uid);

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @return bool
     */
    public function error(): bool;
}