<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 *
 * @since 2.0
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
