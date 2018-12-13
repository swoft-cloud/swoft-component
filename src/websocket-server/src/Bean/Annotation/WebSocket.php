<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Bean\Annotation;

/**
 * Class WebSocket
 * @package Swoft\WebSocket\Server\Bean\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class WebSocket
{
    /**
     * @var string
     */
    private $path = '/';

    /**
     * class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->path = $values['value'];
        }

        if (isset($values['path'])) {
            $this->path = $values['path'];
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
