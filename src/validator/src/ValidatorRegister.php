<?php declare(strict_types=1);


namespace Swoft\Validator;

use Swoft\Validator\Annotation\Mapping\Type;
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
     *                 'type' => XxxType(),
     *                 'annotations' => [
     *                     AnnotationObject(Email/Max/Min),
     *                     AnnotationObject(Email/Max/Min),
     *                 ]
     *            ],
     *         ]
     *     ],
     *     'className' => [
     *         'name' => '',
     *         'class' => '',
     *         'properties' => [
     *            'propName' => [
     *                 'type' => XxxType(),
     *                 'annotations' => [
     *                     AnnotationObject(Email/Max/Min),
     *                     AnnotationObject(Email/Max/Min),
     *                 ]
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
     */
    public static function registerValidatorItem(string $className, string $propertyName, $objAnnotation): void
    {
        if (!isset(self::$validators[$className])) {
            throw new ValidatorException(
                sprintf('%s must be define class `@Validate()`', get_class($objAnnotation))
            );
        }

        $type = self::$validators[$className]['properties'][$propertyName]['type']??[];
        if(!empty($type) && $objAnnotation instanceof Type){
            throw new ValidatorException(
                \sprintf('Only one `@XxxType` can be defined(propterty=%s)!', $propertyName)
            );
        }

        if($objAnnotation instanceof Type){
            self::$validators[$className]['properties'][$propertyName]['type'] = $objAnnotation;
        }

        self::$validators[$className]['properties'][$propertyName]['annotations'][] = $objAnnotation;
    }

    /**
     * @return array
     */
    public static function getValidators(): array
    {
        return self::$validators;
    }
}