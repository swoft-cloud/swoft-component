<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Pipe;

/**
 * The pipe message interface
 */
interface PipeMessageInterface
{
    /**
     * @param string $type
     * @param array  $data
     *
     * @return string
     */
    public function pack(string $type, array $data): string;

    /**
     * @param string $message
     *
     * @return array
     */
    public function unpack(string $message): array;
}
