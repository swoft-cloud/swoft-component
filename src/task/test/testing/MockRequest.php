<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Task\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Task\Request;

/**
 * Class MockRequest
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MockRequest extends Request
{
}
