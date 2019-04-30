<?php declare(strict_types=1);


namespace Swoft\Db\Concern;

use Swoft\Db\EntityRegister;
use Swoft\Db\Exception\EloquentException;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Stdlib\Helper\Str;

/**
 * Trait HasAttributes
 *
 * @package Swoft\Db\Concern
 */
trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The changed model attributes.
     *
     * @var array
     */
    protected $changes = [];

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     * @throws EloquentException
     */
    public function attributesToArray()
    {
        $attributes = [];
        foreach ($this->getArrayableAttributes() as $key => $value) {
            [$pro, $value] = $this->getArrayableItem($key);
            if ($pro !== false) {
                $attributes[$pro] = $value;
            }
        }

        return $attributes;
    }


    /**
     * Get an attribute array of all arrayable attributes.
     *
     * @return array
     */
    protected function getArrayableAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute array of all arrayable values.
     *
     * @param string $key
     *
     * @return array
     * @throws EloquentException
     */
    protected function getArrayableItem(string $key)
    {
        [$pro, $hidden, $value] = $this->getHiddenAttribute($key);
        // hidden status
        $hiddenStatus = $hidden || \in_array($key, $this->getHidden()) || \in_array($pro, $this->getHidden());
        // visible status
        $visibleStatus = \in_array($key, $this->getVisible()) || \in_array($pro, $this->getVisible());

        if ($hiddenStatus === true && $visibleStatus === false) {
            return [false, false];
        }
        return [$pro, $value];
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return array
     * @throws EloquentException
     * @throws \BadMethodCallException
     */
    public function getAttribute(string $key): array
    {
        [$attrName, , , $pro] = $this->getMappingByColumn($key);
        $getter = sprintf('get%s', ucfirst($attrName));

        if (!method_exists($this, $getter)) {
            throw new \BadMethodCallException(
                sprintf('%s method(%s) is not exist!', static::class, $getter)
            );
        }

        $value = $this->{$getter}();
        return [$pro, $value];
    }

    /**
     * Get an not hidden attribute from the model.
     *
     * @param string $key
     *
     * @return array
     * @throws EloquentException
     * @throws \BadMethodCallException
     */
    public function getHiddenAttribute(string $key): array
    {
        [$attrName, , $hidden, $pro] = $this->getMappingByColumn($key);
        $getter = sprintf('get%s', ucfirst($attrName));

        if (!method_exists($this, $getter)) {
            throw new \BadMethodCallException(
                sprintf('%s method(%s) is not exist!', static::class, $getter)
            );
        }

        $value = $this->{$getter}();
        return [$pro, $hidden, $value];
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param        $value
     *
     * @return HasAttributes
     * @throws EloquentException
     */
    public function setAttribute(string $key, $value): self
    {
        [$attrName, $attType] = $this->getMappingByColumn($key);
        $setter = sprintf('set%s', ucfirst($attrName));

        $value = ObjectHelper::parseParamType($attType, $value);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
        }

        return $this;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasSetter($key)
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function setMutatedAttributeValue($key, $value)
    {
        return $this->{'set' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Set a given JSON attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function fillJsonAttribute($key, $value)
    {
        [$key, $path] = explode('->', $key, 2);

        $this->attributes[$key] = $this->asJson($this->getArrayAttributeWithValue(
            $path, $key, $value
        ));

        return $this;
    }

    /**
     * Get an array attribute with the given key and value set.
     *
     * @param string $path
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    protected function getArrayAttributeWithValue(string $path, string $key, $value): array
    {
        $array = $this->getArrayAttributeByKey($key);
        Arr::set($array, str_replace('->', '.', $path), $value);

        return $this->getArrayAttributeByKey($key);
    }

    /**
     * Get an array attribute or return an empty array if it is not set.
     *
     * @param string $key
     *
     * @return array
     */
    protected function getArrayAttributeByKey($key)
    {
        return isset($this->attributes[$key]) ?
            $this->fromJson($this->attributes[$key]) : [];
    }

    /**
     * Encode the given value as JSON.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value);
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @param string $value
     * @param bool   $asObject
     *
     * @return mixed
     */
    public function fromJson($value, $asObject = false)
    {
        return json_decode($value, !$asObject);
    }

    /**
     * Decode the given float.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function fromFloat($value)
    {
        switch ((string)$value) {
            case 'Infinity':
                return INF;
            case '-Infinity':
                return -INF;
            case 'NaN':
                return NAN;
            default:
                return (float)$value;
        }
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];

        $mapping = EntityRegister::getMapping($this->getClassName());
        foreach ($mapping as $attributeName => $map) {
            $getter = sprintf('get%s', ucfirst($attributeName));
            if (!method_exists($this, $getter)) {
                continue;
            }

            $column = $attributeName;
            if (isset($map['column']) && !empty($map['column'])) {
                $column = $map['column'];
            }

            $value = $this->{$getter}();
            if ($value !== null) {
                $attributes[$column] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Set the array of model attributes. No checking is done.
     *
     * @param array $attributes
     * @param bool  $sync
     *
     * @return $this
     * @throws EloquentException
     */
    public function setRawAttributes(array $attributes, $sync = false)
    {
        $this->attributes = $attributes;

        foreach ($attributes as $key => $value) {
            $column = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);
            // not found this key column annotation
            if (empty($column)) {
                unset($this->attributes[$key]);
                continue;
            }
            $type = $column['type'];
            $this->setAttribute($key, ObjectHelper::parseParamType($type, $value));
        }

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Get the model's original attribute values.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed|array
     */
    public function getOriginal($key = null, $default = null)
    {
        return Arr::get($this->original, $key, $default);
    }

    /**
     * Get a subset of the model's attributes.
     *
     * @param array $attributes
     *
     * @return array
     * @throws EloquentException
     */
    public function only(array $attributes)
    {
        $results = [];

        foreach ($attributes as $attribute) {
            $results[$attribute] = $this->getAttribute($attribute);
        }

        return $results;
    }

    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal()
    {
        $this->original = $this->attributes;

        return $this;
    }

    /**
     * Sync a single original attribute with its current value.
     *
     * @param string $attribute
     *
     * @return $this
     */
    public function syncOriginalAttribute($attribute)
    {
        return $this->syncOriginalAttributes($attribute);
    }

    /**
     * Sync multiple original attribute with their current values.
     *
     * @param array|string $attributes
     *
     * @return $this
     */
    public function syncOriginalAttributes($attributes)
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();

        foreach ($attributes as $attribute) {
            $this->original[$attribute] = $this->attributes[$attribute];
        }

        return $this;
    }

    /**
     * Sync the changed attributes.
     *
     * @return $this
     */
    public function syncChanges()
    {
        $this->changes = $this->getDirty();

        return $this;
    }

    /**
     * Determine if the model or given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function isDirty($attributes = null)
    {
        return $this->hasChanges(
            $this->getDirty(), is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Determine if the model or given attribute(s) have remained the same.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function isClean($attributes = null)
    {
        return !$this->isDirty(...func_get_args());
    }

    /**
     * Determine if the model or given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function wasChanged($attributes = null)
    {
        return $this->hasChanges(
            $this->getChanges(), is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Determine if the given attributes were changed.
     *
     * @param array             $changes
     * @param array|string|null $attributes
     *
     * @return bool
     */
    protected function hasChanges($changes, $attributes = null)
    {
        // If no specific attributes were provided, we will just see if the dirty array
        // already contains any attributes. If it does we will just return that this
        // count is greater than zero. Else, we need to check specific attributes.
        if (empty($attributes)) {
            return count($changes) > 0;
        }

        // Here we will spin through every attribute and see if this is in the array of
        // dirty attributes. If it is, we will return true and if we make it through
        // all of the attributes for the entire array we will return false at end.
        foreach (Arr::wrap($attributes) as $attribute) {
            if (array_key_exists($attribute, $changes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = [];

        foreach ($this->getAttributes() as $key => $value) {
            if (!$this->originalIsEquivalent($key, $value)) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if the new and old values for a given key are equivalent.
     *
     * @param string $key
     * @param mixed  $current
     *
     * @return bool
     */
    protected function originalIsEquivalent($key, $current)
    {
        if (!array_key_exists($key, $this->original)) {
            return false;
        }

        $original = $this->getOriginal($key);

        if ($current === $original) {
            return true;
        } elseif (is_null($current)) {
            return false;
        }

        return is_numeric($current) && is_numeric($original)
            && strcmp((string)$current, (string)$original) === 0;
    }

    /**
     * Get the attributes that were changed.
     *
     * @return array
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * @param string $key
     *
     * @return array
     * @throws EloquentException
     */
    private function getMappingByColumn(string $key): array
    {
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);

        if (empty($mapping)) {
            throw new EloquentException(sprintf('Column(%s) is not exist!', $key));
        }

        $attrName = $mapping['attr'];
        $type     = $mapping['type'];
        $hidden   = $mapping['hidden'];
        $pro      = $mapping['pro'];

        return [$attrName, $type, $hidden, $pro];
    }
}
