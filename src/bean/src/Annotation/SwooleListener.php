<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Annotation;

use Swoft\Bootstrap\SwooleEvent;

/**
 * @Annotation
 * @Target("CLASS")
 */
class SwooleListener
{
    private $event = [];

    private $type = SwooleEvent::TYPE_SERVER;

    private $order = 0;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->event = (array)$values['value'];
        }

        if (isset($values['event'])) {
            $this->event = (array)$values['event'];
        }

        if (isset($values['type'])) {
            $this->type = $values['type'];
        }

        if (isset($values['order'])) {
            $this->order = $values['order'];
        }
    }

    public function getEvent(): array
    {
        return $this->event;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
