<?php

namespace Swoft\Bean\Definition\Parser;

use Swoft\Bean\Definition\ObjectDefinition;

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
     * Class all bean names (many instances)
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'beanName',
     *         'beanName',
     *         'beanName',
     *     ]
     * ]
     */
    protected $classNames = [];

    /**
     * ObjectParser constructor.
     *
     * @param array $definitions
     * @param array $objectDefinitions
     * @param array $classNames
     */
    public function __construct(array $definitions, array $objectDefinitions, array $classNames)
    {
        $this->definitions       = $definitions;
        $this->objectDefinitions = $objectDefinitions;
        $this->classNames        = $classNames;
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