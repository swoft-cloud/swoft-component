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

    const NUMBER = 'number';

    const STRING = 'string';

    const FLOAT = 'float';

    const BOOLEAN = 'boolean';

    const BOOL = 'bool';

    const MAPPING = [
        self::INT => PhpTypes::INTEGER,
        self::NUMBER => PhpTypes::INTEGER,
        self::STRING => PhpTypes::STRING,
        self::FLOAT => PhpTypes::FLOAT,
        self::BOOL => PhpTypes::BOOLEAN,
        self::BOOLEAN => PhpTypes::BOOLEAN,
    ];
}
