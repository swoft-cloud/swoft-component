<?php

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
}