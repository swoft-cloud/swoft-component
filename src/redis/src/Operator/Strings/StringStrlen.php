<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Strings;

use Swoft\Redis\Operator\Command;

class StringStrlen extends Command
{
    /**
     * [String] strLen
     *
     * @return string
     */
    public function getId()
    {
        return 'strLen';
    }
}
