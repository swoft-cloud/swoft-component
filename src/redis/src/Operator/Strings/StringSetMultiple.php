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

use Swoft\Redis\Operator\Command;

class StringSetMultiple extends Command
{
    /**
     * [String] mSet
     *
     * @return string
     */
    public function getId()
    {
        return 'mSet';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        if (count($arguments) === 1 && is_array($arguments[0])) {
            $flattenedKVs = [];
            $args = $arguments[0];

            foreach ($args as $k => $v) {
                $flattenedKVs[$k] = $v;
            }

            return $flattenedKVs;
        }

        return $arguments;
    }
}
