<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-01
 * Time: 19:57
 */

namespace Swoft\Event\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Listener annotation - for mark an event listener handler class.
 * @since 2.0
 * @package Swoft\Event\Annotation\Mapping
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("event", type="string"),
 * })
 */
final class Listener
{
    /**
     * Listen event name
     *
     * @var string
     * @Required()
     */
    private $event = '';

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->event = (string)$values['value'];
        }

        if (isset($values['event'])) {
            $this->event = $values['event'];
        }
    }

    /**
     * 监听事件
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }
}
