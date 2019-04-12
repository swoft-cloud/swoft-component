<?php declare(strict_types=1);

namespace Swoft\Stdlib\Helper;

/**
 * Object helper
 *
 * @since 2.0
 */
class ObjectHelper
{
    /**
     * Return object hash value
     *
     * @param object $object
     *
     * @return string
     */
    public static function hash($object): string
    {
        return \spl_object_hash($object);
    }

    /**
     * Set the property value for the object
     * - Will try to set properties using the setter method
     * - Then, try to set properties directly
     *
     * @param mixed $object An object instance
     * @param array $options
     *
     * @return mixed
     */
    public static function init($object, array $options)
    {
        foreach ($options as $property => $value) {
            if (\is_numeric($property)) {
                continue;
            }

            $setter = 'set' . \ucfirst($property);

            // has setter
            if (\method_exists($object, $setter)) {
                $object->$setter($value);
            } elseif (\property_exists($object, $property)) {
                $object->$property = $value;
            }
        }

        return $object;
    }

    /**
     * Parse the type of binding param
     *
     * @param string $type  the type of param
     * @param mixed  $value the value of param
     *
     * @return mixed
     */
    public static function parseParamType(string $type, $value)
    {
        switch ($type) {
            case 'integer':
            case 'int':
                $value = (int)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'double':
                $value = (double)$value;
                break;
        }

        return $value;
    }

    /**
     * Get default by type
     *
     * @param string
     *
     * @return mixed
     */
    public static function getDefaultValue(string $type)
    {
        $value = null;
        switch ($type) {
            case 'int':
                $value = 0;
                break;
            case 'string':
                $value = '';
                break;
            case 'bool':
                $value = false;
                break;
            case 'float':
                $value = 0;
                break;
            case 'double':
                $value = 0;
                break;
        }

        return $value;
    }
}