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

namespace Swoft\Trace;

use Swoft\Bean\Annotation\Bean;
use Swoft\Core\RequestContext;

/**
 * @Bean
 */
class Tracer
{
    /**
     * @param string $method
     */
    public function trace(string $method)
    {
        RequestContext::setContextDataByKey('callStack', array_merge($this->getCallStack(), [$method]));
    }

    /**
     * @return array
     */
    public function getCallStack(): array
    {
        return (array)RequestContext::getContextDataByKey('callStack');
    }
}
