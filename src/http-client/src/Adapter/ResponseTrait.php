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

namespace Swoft\HttpClient\Adapter;

use Swoft\App;
use Swoft\Http\Message\Base\Response as BaseResponse;
use Swoft\Http\Message\Testing\Base\Response as TestingBaseResponse;

trait ResponseTrait
{
    /**
     * @return BaseResponse|TestingBaseResponse
     * @throws \RuntimeException
     */
    protected function createResponse()
    {
        return App::$isInTest ? new TestingBaseResponse() : new BaseResponse();
    }
}
