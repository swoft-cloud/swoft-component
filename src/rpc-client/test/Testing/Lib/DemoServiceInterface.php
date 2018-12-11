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
namespace SwoftTest\RpcClient\Testing\Lib;

use Swoft\Core\ResultInterface;

/**
 * Interface DemoServiceInterface
 * @method ResultInterface deferVersion()
 * @method ResultInterface deferLongMessage($string)
 * @method ResultInterface deferGet($id)
 */
interface DemoServiceInterface
{
    public function version();

    public function longMessage($string);

    public function get($id);
}
