<?php

namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD"})
 */
class Inject
{
    /**
     * The bean that wanna inject
     */
    private $name = '';

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
