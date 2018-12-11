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

namespace Swoft\Db;

use Swoft\Core\Types as PhpTypes;

/**
 * PHP types
 */
class Types
{
    const INT = 'int';

    const INTEGER = 'integer';

    const BIGINT = 'bigint';

    const SMALLINT = 'smallint';

    const TINYINT = 'tinyint';

    const NUMBER = 'number';

    const STRING = 'string';

    const FLOAT = 'float';

    const DATETIME = 'datetime';

    const BOOLEAN = 'boolean';

    const BOOL = 'bool';

    public static function getPhpType($type)
    {
        $ret = PhpTypes::UNKNOWN;

        switch ($type) {
            case self::INT:
            case self::INTEGER:
            case self::BIGINT:
            case self::SMALLINT:
            case self::TINYINT:
            case self::NUMBER:
                $ret = PhpTypes::INTEGER;
                break;
            case self::STRING:
            case self::DATETIME:
                $ret = PhpTypes::STRING;
                break;
            case self::FLOAT:
                $ret = PhpTypes::FLOAT;
                break;
            case self::BOOLEAN:
            case self::BOOL:
                $ret = PhpTypes::BOOLEAN;
                break;
            default:
                break;
        }

        return $ret;
    }
}
