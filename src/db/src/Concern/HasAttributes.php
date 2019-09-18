<?php declare(strict_types=1);


namespace Swoft\Db\Concern;

use BadMethodCallException;
use Swoft\Db\EntityRegister;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Query\Expression;
use Swoft\Db\Schema\Grammars\Grammar;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Stdlib\Helper\Str;
use TypeError;
use function in_array;

/**
 * Trait HasAttributes
 *
 * @since 2.0
 */
trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $modelAttributes = [];

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $modelOriginal = [];

    /**
     * The changed model attributes.
     *
     * @var array
     */
    protected $modelChanges = [];

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     * @throws DbException
     */
    public function attributesToArray(): array
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
     * Get model attributes value
     *
     * @return array
     */
    public function getModelAttributesValue(): array
    {
        return $this->modelAttributes;
    }

    /**
     * Get an attribute array of all arrayable attributes.
     *
     * @return array
     */
    public function getArrayableAttributes(): array
    {
        return array_merge($this->modelAttributes, $this->getModelAttributes());
    }

    /**
     * Get an attribute array of all arrayable values.
     *
     * @param string $key
     *
     * @return array
     * @throws DbException
     */
    protected function getArrayableItem(string $key): array
    {
        [$pro, $hidden, $value] = $this->getHiddenAttribute($key);

        // hidden status
        $hiddenStatus = $hidden
            || in_array($key, $this->getModelHidden(), true)
            || in_array($pro, $this->getModelHidden(), true);

        // visible status
        $visibleStatus = in_array($key, $this->getModelVisible(), true)
            || in_array($pro, $this->getModelVisible(), true);

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
     * @throws DbException
     */
    public function getModelAttribute(string $key): array
    {
        [$attrName, , , $pro] = $this->getMappingByColumn($key);
        $getter = sprintf('get%s', ucfirst($attrName));

        if (!method_exists($this, $getter)) {
            throw new BadMethodCallException(
                sprintf('%s method(%s) is not exist!', static::class, $getter)
            );
        }

        $value = $this->{$getter}();
        return [$pro, $value];
    }

    /**
     * Get an attribute value from the model.
     *
     * @param string $key
     *
     * @return mixed
     * @throws DbException
     */
    public function getAttributeValue(string $key)
    {
        return $this->getModelAttribute($key)[1];
    }

    /**
     * Get an not hidden attribute from the model.
     *
     * @param string $key
     *
     * @return array
     * @throws DbException
     */
    public function getHiddenAttribute(string $key): array
    {
        [$attrName, , $hidden, $pro] = $this->getMappingByColumn($key);
        $getter = sprintf('get%s', ucfirst($attrName));

        if (!method_exists($this, $getter)) {
            throw new BadMethodCallException(
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
    public function hasGetter(string $key): bool
    {
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);
        $getter  = sprintf('get%s', ucfirst($mapping['attr'] ?? $key));

        return method_exists($this, $getter);
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute(string $key, $value)
    {
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);

        $getter = sprintf('get%s', ucfirst($mapping['attr'] ?? $key));

        return $this->{$getter}($value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param        $value
     *
     * @return HasAttributes
     * @throws DbException
     */
    public function setModelAttribute(string $key, $value): self
    {
        [$attrName, $attType] = $this->getMappingByColumn($key);
        $setter = sprintf('set%s', ucfirst($attrName));

        $value = ObjectHelper::parseParamType($attType, $value);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);

            $this->modelAttributes[$key] = $value;
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
    public function hasSetter($key): bool
    {
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);
        $setter  = sprintf('set%s', ucfirst($mapping['attr'] ?? $key));

        return method_exists($this, $setter);
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
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);
        $setter  = sprintf('set%s', ucfirst($mapping['attr'] ?? $key));

        return $this->{$setter}($value);
    }

    /**
     * Set a given JSON attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function fillJsonAttribute($key, $value): self
    {
        [$key, $path] = explode('->', $key, 2);

        $this->modelAttributes[$key] = $this->asJson($this->getArrayAttributeWithValue(
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
    protected function getArrayAttributeByKey(string $key): array
    {
        return isset($this->modelAttributes[$key]) ?
            $this->fromJson($this->modelAttributes[$key]) : [];
    }

    /**
     * Encode the given value as JSON.
     *
     * @param     $value
     * @param int $option
     *
     * @return string
     */
    protected function asJson($value, int $option = JSON_UNESCAPED_UNICODE): string
    {
        // Compatible MySQL `json_set()` method
        return $value ? json_encode($value, $option) : '{}';
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @param string $value
     * @param bool   $asObject
     *
     * @return mixed
     */
    public function fromJson($value, bool $asObject = false)
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
    public function getModelAttributes(): array
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

            try {
                $value = $this->{$getter}();
                if ($value !== null) {
                    $attributes[$column] = $value;
                }
            } catch (TypeError $e) {
                unset($e);
                continue;
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
     * @throws DbException
     */
    public function setRawAttributes(array $attributes, bool $sync = false): self
    {
        foreach ($this->getSafeAttributes($attributes, false) as $key => $value) {
            if ($value instanceof Expression || $this->isJsonSelector($key)) {
                $this->modelAttributes[$key] = $value;
                continue;
            }

            $this->setModelAttribute($key, $value);
        }

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Get safe model attributes
     *
     * @param array $attributes
     * @param bool  $encode
     *
     * @return array
     */
    public function getSafeAttributes(array $attributes, bool $encode = true): array
    {
        $safeAttributes = [];

        // Get `@Column` Prop Mapping
        $props = EntityRegister::getProps($this->getClassName());

        foreach ($attributes as $key => $value) {
            $key = (string)$key;

            // Check is json field
            if ($this->isJsonSelector($key)) {
                $safeAttributes[$key] = $value;
                continue;
            }

            $key = $props[$key] ?? $key;

            // Get `@Column` Mapping
            $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);
            // Not found this key mapping annotation
            if (empty($mapping)) {
                continue;
            }

            // Not handler expression
            if (!$value instanceof Expression) {
                $type  = $mapping['type'];
                $value = ObjectHelper::parseParamType($type, $value);

                if ($type === Grammar::ARRAY && $encode === true && !is_scalar($value)) {
                    // Array to string
                    $value = $this->asJson($value);
                }
            }
            $safeAttributes[$key] = $value;
        }
        return $safeAttributes;
    }

    /**
     * Determine if the given string is a JSON selector.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isJsonSelector(string $value): bool
    {
        return Str::contains($value, '->');
    }


    /**
     * Get the model's original attribute values.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed|array
     */
    public function getModelOriginal(string $key = null, $default = null)
    {
        return Arr::get($this->modelOriginal, $key, $default);
    }

    /**
     * Get a subset of the model's attributes.
     *
     * @param array $attributes
     *
     * @return array
     * @throws DbException
     */
    public function only(array $attributes): array
    {
        $results = [];

        foreach ($attributes as $attribute) {
            $results[$attribute] = $this->getAttributeValue($attribute);
        }

        return $results;
    }

    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal(): self
    {
        $attributes = $this->getArrayableAttributes();

        $this->modelAttributes = $attributes;
        $this->modelOriginal   = $attributes;

        return $this;
    }

    /**
     * Sync a single original attribute with its current value.
     *
     * @param string $attribute
     *
     * @return $this
     */
    public function syncOriginalAttribute($attribute): self
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
    public function syncOriginalAttributes($attributes): self
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();

        foreach ($attributes as $attribute) {
            $this->modelOriginal[$attribute] = $this->modelAttributes[$attribute];
        }

        return $this;
    }

    /**
     * Sync the changed attributes.
     *
     * @return $this
     */
    public function syncChanges(): self
    {
        $this->modelChanges = $this->getDirty();

        return $this;
    }

    /**
     * Determine if the model or given attribute(s) have been modified.
     *
     * @param array|string|null $attributes
     *
     * @return bool
     */
    public function isDirty($attributes = null): bool
    {
        return $this->hasSwoftChanges(
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
    public function isClean($attributes = null): bool
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
    public function wasChanged($attributes = null): bool
    {
        return $this->hasSwoftChanges(
            $this->getModelChanges(), is_array($attributes) ? $attributes : func_get_args()
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
    protected function hasSwoftChanges(array $changes, $attributes = null): bool
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
    public function getDirty(): array
    {
        $dirty = [];

        foreach ($this->getModelAttributes() as $key => $value) {
            if (!$this->originalIsEquivalent($key, $value)) {
                $dirty[$key] = $value;
            }
        }

        foreach ($this->modelAttributes as $key => $value) {
            if ($value instanceof Expression || !$this->originalIsEquivalent($key, $value)) {
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
    protected function originalIsEquivalent(string $key, $current): bool
    {
        if (!array_key_exists($key, $this->modelOriginal)) {
            return false;
        }

        $original = $this->getModelOriginal($key);

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
    public function getModelChanges(): array
    {
        return $this->modelChanges;
    }

    /**
     * @param string $key
     *
     * @return array
     * @throws DbException
     */
    private function getMappingByColumn(string $key): array
    {
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), $key);

        if (empty($mapping)) {
            throw new DbException(sprintf('Column(%s) is not exist!', $key));
        }

        $attrName = $mapping['attr'];
        $type     = $mapping['type'];
        $hidden   = $mapping['hidden'];
        $pro      = $mapping['pro'];

        return [$attrName, $type, $hidden, $pro];
    }
}
