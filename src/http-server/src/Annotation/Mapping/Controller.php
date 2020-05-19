<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Controller
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("prefix", type="string"),
 * })
 *
 * @since 2.0
 */
final class Controller
{
    /**
     * Route group prefix for the controller
     *
     * @Required()
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Controller constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->prefix = $values['value'];
        }
        if (isset($values['prefix'])) {
            $this->prefix = $values['prefix'];
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
