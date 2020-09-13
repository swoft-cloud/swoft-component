<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Bean
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("scope", type="string"),
 *     @Attribute("alias", type="string"),
 * })
 *
 * @since 2.0
 */
final class Bean
{
    /**
     * Singleton bean
     */
    public const SINGLETON = 'singleton';

    /**
     * New bean
     */
    public const PROTOTYPE = 'prototype';

    /**
     * New bean from every request
     */
    public const REQUEST = 'request';

    /**
     * New bean for one session
     */
    public const SESSION = 'session';

    /**
     * Bean name
     *
     * @var string
     */
    private $name = '';

    /**
     * Bean scope
     *
     * @var string
     * @Enum({Bean::SINGLETON, Bean::PROTOTYPE, Bean::REQUEST})
     */
    private $scope = self::SINGLETON;

    /**
     * Bean alias
     *
     * @var string
     */
    private $alias = '';

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['scope'])) {
            $this->scope = $values['scope'];
        }
        if (isset($values['alias'])) {
            $this->alias = $values['alias'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }
}
