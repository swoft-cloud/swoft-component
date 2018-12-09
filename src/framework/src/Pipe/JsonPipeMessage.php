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

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\JsonHelper;

/**
 * Json pipe message
 *
 * @Bean
 */
class JsonPipeMessage implements PipeMessageInterface
{
    /**
     * @param string $type
     * @param array  $data
     *
     * @return string
     */
    public function pack(string $type, array $data): string
    {
        $data = [
            'pipeType' => $type,
            'message' => $data,
        ];

        return JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string $message
     *
     * @return array
     */
    public function unpack(string $message): array
    {
        $messageAry = json_decode($message, true);
        $type = $messageAry['pipeType'];
        $data = $messageAry['message'];

        return [$type, $data];
    }
}
