<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Sets;

use Swoft\Redis\Operator\Command;

class SetRemove extends Command
{
    /**
     * [Set] sRemove - sRem
     *
     * @return string
     */
    public function getId()
    {
        return 'sRemove';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        return self::normalizeVariadic($arguments);
    }
}
