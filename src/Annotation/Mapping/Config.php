<?php

namespace Swoft\Config\Annotation;

/**
 * Class Config
 *
 * @Annotation
 * @Target({"CLASS","PROPERTY"})
 *
 * @since 2.0
 */
class Config
{
    /**
     * @var string
     */
    private $key = '';

    /**
     * Config constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->key = $values['value'];
        }
        if (isset($values['name'])) {
            $this->key = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}