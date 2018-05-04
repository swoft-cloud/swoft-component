<?php

namespace Swoft\Http\Server\Bean\Annotation;

/**
 * Controller annotation label
 *
 * @Annotation
 * @Target("CLASS")
 */
class Controller
{
    /**
     * @var string Route prefix of the controller
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
