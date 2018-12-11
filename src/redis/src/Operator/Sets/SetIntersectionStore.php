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
namespace Swoft\Redis\Operator\Sets;

use Swoft\Redis\Operator\Command;

class SetIntersectionStore extends Command
{
    /**
     * [Set] sInterStore
     *
     * @return string
     */
    public function getId()
    {
        return 'sInterStore';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        if (count($arguments) === 2 && is_array($arguments[1])) {
            return array_merge([$arguments[0]], $arguments[1]);
        }

        return $arguments;
    }
}
