<?php declare(strict_types=1);

namespace Swoft\Stdlib;

/**
 * Class PhpType
 *
 * @since 2.0.1
 */
abstract class PhpType
{
    // Basic data type
    public const INT     = 'int';
    public const BOOL    = 'bool';
    public const BOOLEAN = 'boolean';
    public const INTEGER = 'integer';
    public const FLOAT   = 'float';
    public const STRING  = 'string';

    // Complex data type
    public const ARRAY   = 'array';
    public const OBJECT  = 'object';

    /**
     * @param string $type
     * @param mixed $value
     *
     * @return mixed
     */
    public static function convertType(string $type, $value)
    {
        switch ($type) {
            case self::BOOL:
            case self::BOOLEAN:
                $value = (bool)$value;
                break;
            case self::INT:
            case self::INTEGER:
                $value = (int)$value;
                break;
            case self::FLOAT:
                $value = (float)$value;
                break;
            case self::STRING:
                $value = (string)$value;
                break;

        }

        return $value;
    }
}
