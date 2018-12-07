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

namespace Swoft\Console\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Command
{
    private $name = '';

    private $enabled = true;

    private $coroutine = true;

    private $server = false;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }

        if (isset($values['name'])) {
            $this->name = $values['name'];
        }

        if (isset($values['coroutine'])) {
            $this->coroutine = $values['coroutine'];
        }

        if (isset($values['enabled'])) {
            $this->enabled = (bool)$values['enabled'];
        }

        if (isset($values['server'])) {
            $this->server = $values['server'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isCoroutine(): bool
    {
        return $this->coroutine;
    }

    public function isServer(): bool
    {
        return $this->server;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
