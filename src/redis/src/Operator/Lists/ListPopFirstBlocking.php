<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\Lists;

use Swoft\Redis\Operator\Command;

class ListPopFirstBlocking extends Command
{
    /**
     * [List] blPop
     *
     * @return string
     */
    public function getId()
    {
        return 'blPop';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        if (count($arguments) === 2 && is_array($arguments[0])) {
            list($arguments, $timeout) = $arguments;
            array_push($arguments, $timeout);
        }

        return $arguments;
    }
}
