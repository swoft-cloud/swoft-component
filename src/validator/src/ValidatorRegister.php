<?php declare(strict_types=1);


namespace Swoft\Validator;

use hxh\components\Validate;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class ValidatorRegister
 *
 * @since 2.0
 */
class ValidatorRegister
{
    /**
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'name' => '',
     *         'class' => '',
     *         'properties' => [
     *            'propName' => [
     *                 'type' => 'int/string',
     *                 'annotation' => AnnotationObject(Email/Max/Min)
     *            ],
     *         ]
     *     ],
     *     'className' => [
     *         'name' => '',
     *         'class' => '',
     *         'properties' => [
     *            'propName' => [
     *                 'type' => 'int/string',
     *                 'annotation' => AnnotationObject(Email/Max/Min)
     *            ],
     *         ]
     *     ],
     * ]
     */
    private static $validators = [];

    /**
     * @param string $className
     * @param string $validatorName
     */
    public static function registerValidator(string $className, string $validatorName): void
    {
        self::$validators[$className] = [
            'name'  => $validatorName,
            'class' => $className
        ];
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param object $objAnnotation
     *
     * @throws ValidatorException
     * @throws \ReflectionException
     */
    public static function registerValidatorItem(string $className, string $propertyName, $objAnnotation): void
    {
        if (!isset(self::$validators[$className])) {
            throw new ValidatorException(
                sprintf('%s must be define class `@Validate()`', get_class($objAnnotation))
            );
        }

        // Get property document type
        $type = Validator::getPropertyType($className, $propertyName);

        // Save record
        self::$validators[$className]['properties'][$propertyName] = [
            'type'       => $type,
            'annotation' => $objAnnotation,
        ];
    }

    /**
     * @return array
     */
    public static function getValidators(): array
    {
        return self::$validators;
    }
}