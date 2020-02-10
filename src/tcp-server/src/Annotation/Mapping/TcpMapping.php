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
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class TcpMapping - Use for mark tcp message request command handler method
 *
 * @since   2.0.3
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes(
 *     @Attribute("route", type="string")
 * )
 */
final class TcpMapping
{
    /**
     * The tcp server route path. eg: 'home.index'
     *
     * @var string
     * @Required()
     */
    private $route = '';

    /**
     * Mark current route is root command.
     * If True, will dont prepend prefix for the command.
     *
     * @var bool
     */
    private $root = false;

    /**
     * Middleware for the method
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
            $this->route = (string)$values['value'];
        } elseif (isset($values['route'])) {
            $this->route = (string)$values['route'];
        }

        if (isset($values['root'])) {
            $this->root = (bool)$values['root'];
        }

        if (isset($values['middlewares'])) {
            $this->middlewares = (array)$values['middlewares'];
        }
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->root;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
