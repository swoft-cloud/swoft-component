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
 * the annotation of cache put
 *
 * @Annotation
 * @Target("METHOD")
 */
class CachePut
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int;
     */
    private $ttl;

    /**
     * @var string
     */
    private $condition;

    /**
     * CachePut constructor.
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
        if (isset($values['key'])) {
            $this->key = $values['key'];
        }
        if (isset($values['ttl'])) {
            $this->ttl = $values['ttl'];
        }
        if (isset($values['condition'])) {
            $this->condition = $values['condition'];
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
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }
}
