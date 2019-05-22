<?php declare(strict_types=1);


namespace Swoft\Validator;

/**
 * Class ValidateRegister
 *
 * @since 2.0
 */
class ValidateRegister
{
    /**
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'methodName' => [
     *              'validatorName' => [
     *                  'validator' => 'validatorName',
     *                  'fields' => ['a', 'b']
     *                  'params' => [1,2]
     *                  'message' => 'Fail message'.
     *              ]
     *          ]
     *     ]
     * ]
     */
    private static $validates = [];

    /**
     * @param string $className
     * @param string $method
     * @param string $validator
     * @param array  $fields
     * @param array  $params
     * @param string $message
     */
    public static function registerValidate(
        string $className,
        string $method,
        string $validator,
        array $fields,
        array $params,
        string $message
    ): void {
        self::$validates[$className][$method][$validator] = [
            'validator' => $validator,
            'fields'    => $fields,
            'params'    => $params,
            'message'   => $message
        ];
    }

    /**
     * @param string $className
     * @param string $method
     *
     * @return array
     */
    public static function getValidates(string $className, string $method): array
    {
        return self::$validates[$className][$method] ?? [];
    }
}