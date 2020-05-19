<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class HeaderController
 *
 * @since 2.0
 *
 * @Controller("testHeader")
 */
class HeaderController
{
    /**
     * @RequestMapping()
     *
     * @param Request $request
     *
     * @return array
     */
    public function headerLines(Request $request): array
    {
        return $request->getHeaderLines();
    }
}
