<?php declare(strict_types=1);


namespace Swoft\Validator;

use PhpDocReader\PhpDocReader;

/**
 * Class Validator
 *
 * @since 2.0
 */
class Validator
{
    /**
     * @param array  $data
     * @param string $className
     * @param string $method
     *
     * @return bool
     */
    public static function validate(array $data, string $className, string $method): bool
    {
        $validates = ValidateRegister::getValidates($className, $method);
        if (empty($validates)) {
            return true;
        }

        return false;
    }
}