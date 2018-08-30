<?php

namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Bean
{

    private $name = '';

    private $scope = Scope::SINGLETON;

    private $ref = '';

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
        if (isset($values['ref'])) {
            $this->ref = $values['ref'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScope(): int
    {
        return $this->scope;
    }

    public function getRef(): string
    {
        return $this->ref;
    }
}
