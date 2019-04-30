<?php declare(strict_types=1);


namespace Swoft\Validator;

use PhpDocReader\PhpDocReader;

/**
 * Class Validator
 *
 * @since 2.0
 */
class Validator
{
    /**
     * Base types
     */
    public const PROP_TYPES = [
        'bool'    => self::PROP_BOOL,
        'boolean' => self::PROP_BOOL,
        'string'  => self::PROP_STRING,
        'int'     => self::PROP_INT,
        'integer' => self::PROP_INT,
        'float'   => self::PROP_FLOAT,
        'double'  => self::PROP_DOUBLE,
        'array'   => self::PROP_ARRAY,
    ];

    /**
     * Property int
     */
    public const PROP_INT = 'int';

    /**
     * Property bool
     */
    public const PROP_BOOL = 'bool';

    /**
     * Property float
     */
    public const PROP_FLOAT = 'float';

    /**
     * Property double
     */
    public const PROP_DOUBLE = 'double';

    /**
     * Property string
     */
    public const PROP_STRING = 'string';

    /**
     * Property array
     */
    public const PROP_ARRAY = 'array';

    /**
     * @param string $className
     * @param string $propertyName
     *
     * @return string
     * @throws \ReflectionException
     */
    public static function getPropertyType(string $className, string $propertyName): string
    {
        // Parse php document
        $type      = null;
        $phpReader = new PhpDocReader();
        $property  = new \ReflectionProperty($className, $propertyName);

        // Get the content of the @var annotation
        if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
            list(, $type) = $matches;
        }

        if (empty($type)) {
            return '';
        }

        return self::PROP_TYPES[$type] ?? '';
    }

    public static function validate(string $className, string $method): bool
    {
        $validates = ValidateRegister::getValidates($className, $method);
        if (empty($validates)) {
            return true;
        }

        return false;
    }
}