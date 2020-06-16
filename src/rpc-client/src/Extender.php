<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Contract\ExtenderInterface;
use function context;

/**
 * Class Extender
 *
 * @since 2.0
 *
 * @Bean(name="rpcClientExtender")
 */
class Extender implements ExtenderInterface
{
    /**
     * @return array
     */
    public function getExt(): array
    {
        return [
            'traceid'  => context()->get('traceid', ''),
            'spanid'   => context()->get('spanid', ''),
            'parentid' => context()->get('parentid', ''),
            'extra'    => context()->get('extra', null),
        ];
    }
}
