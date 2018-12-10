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

namespace SwoftTest\HttpClient\Cases;

use PHPUnit\Framework\TestCase;
use Swoft\App;

class AbstractTestCase extends TestCase
{
    public function getOptions()
    {
        if (App::isCoContext()) {
            return ['timeout' => 5];
        }

        return [
            CURLOPT_TIMEOUT => 5,
        ];
    }
}
