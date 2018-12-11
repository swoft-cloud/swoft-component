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
namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class BootBean
{
    private $server = false;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->server = $values['value'];
        }
        if (isset($values['server'])) {
            $this->server = $values['server'];
        }
    }

    public function isServer(): bool
    {
        return $this->server;
    }
}
