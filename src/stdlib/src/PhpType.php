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
}
