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
namespace Swoft\Redis\Operator\Keys;

use Swoft\Redis\Operator\Command;

class KeyRandom extends Command
{
    /**
     * [Keys] randomKey
     *
     * @return string
     */
    public function getId()
    {
        return 'randomKey';
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($data)
    {
        return $data !== '' ? $data : null;
    }
}
