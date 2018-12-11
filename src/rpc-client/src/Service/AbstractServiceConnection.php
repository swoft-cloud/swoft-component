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
namespace Swoft\Rpc\Client\Service;

use Swoft\Pool\AbstractConnection;

/**
 * Abstract service connection
 */
abstract class AbstractServiceConnection extends AbstractConnection implements ServiceConnectInterface
{
    /**
     * Close connection
     * @return bool
     */
    public function close()
    {
        return true;
    }
}
