<?php

namespace Swoft\Bean\Definition\Parser;

/**
 * Class ObjectParser
 *
 * @since 2.0
 */
class ObjectParser
{
    /**
     * All definitions
     *
     * @var array
     *
     * @example
     * [
     *     'name' => [
     *         'class' => 'className',
     *         [
     *             'construnctArg',
     *             '${ref.name}', // config params
     *             '${beanName}', // object
     *         ],
     *         'propertyValue',
     *         '${ref.name}',
     *         '${beanName}',
     *         '__option' => [
     *              'scope' => '...',
     *              'alias' => '...',
     *         ]
     *     ]
     * ]
     */
    protected $definitions = [];

    /**
     * Bean definitions
     *
     * @var ObjectDefinition[]
     *
     * @example
     * [
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition,
     *     'beanName' => new ObjectDefinition
     * ]
     */
    protected $objectDefinitions = [];

    /**
     * ObjectParser constructor.
     *
     * @param array $definitions
     * @param array $objectDefinitions
     */
    public function __construct(array $definitions, array $objectDefinitions)
    {
        $this->definitions       = $definitions;
        $this->objectDefinitions = $objectDefinitions;
    }

    /**
     * Get value by reference
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function getValueByRef($value): array
    {
        if (!is_string($value)) {
            return [$value, false];
        }

        // Reg match
        $isRef = preg_match('/^\$\{(.*)\}$/', $value, $match);
        if ($isRef && isset($match[1])) {
            return [$match[1], $isRef];
        }

        return [$value, false];
    }
}