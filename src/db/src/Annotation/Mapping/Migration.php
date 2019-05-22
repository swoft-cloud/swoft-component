<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Migration
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("time", type="int"),
 * })
 *
 * @since 2.0
 */
class Migration
{
    /**
     * @var int
     */
    private $time = 0;

    /**
     * Migration constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->time = $values['value'];
        }

        if (isset($values['time'])) {
            $this->time = $values['time'];
        }
    }
}