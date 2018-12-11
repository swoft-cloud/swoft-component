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
namespace Swoft\Redis\Operator\Strings;

class StringPreciseSetExpire extends StringSetExpire
{
    /**
     * [String] psetEx
     *
     * @return string
     */
    public function getId()
    {
        return 'psetEx';
    }
}
