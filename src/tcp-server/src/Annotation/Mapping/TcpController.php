<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class TcpController
 *
 * @since 2.0.3
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("prefix", type="string")
 * )
 */
final class TcpController
{
    /**
     * Controller prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Middlewares for the tcp controller. It's classname or bean-name
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->prefix = (string)$values['value'];
        } elseif (isset($values['prefix'])) {
            $this->prefix = (string)$values['prefix'];
        }

        if (isset($values['middlewares'])) {
            $this->middlewares = (array)$values['middlewares'];
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
