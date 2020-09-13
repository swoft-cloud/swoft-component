<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Unit;

use Swoft\Validator\ValidateRegister;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getValidates(string $className, string $method): array
    {
        return ValidateRegister::getValidates($className, $method);
    }
}
