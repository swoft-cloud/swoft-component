<?php declare(strict_types=1);


namespace Swoft\Db\Eloquent;

use Swoft\Bean\Exception\PrototypeException;
use Swoft\Db\Concern\HasAttributes;
use Swoft\Db\Concern\HidesAttributes;
use Swoft\Db\Connection;
use Swoft\Db\DB;
use Swoft\Db\EntityRegister;
use Swoft\Db\Exception\EloquentException;
use Swoft\Db\Exception\EntityException;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
use Swoft\Db\Query\Builder as QueryBuilder;
use Swoft\Stdlib\Arrayable;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Stdlib\Helper\Str;
use Swoft\Stdlib\Jsonable;

/**
 * Class Model
 *
 * @since 2.0
 */
abstract class Model implements \ArrayAccess, Arrayable, Jsonable, \JsonSerializable
{
    use HidesAttributes, HasAttributes;

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;

    /**
     * Indicates if the model exists.
     *
     * @var bool
     */
    public $exists = false;

    /**
     * Create a new EloquentException model instance.
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->syncOriginal();

        $this->fill($attributes);
    }


    /**
     * Create a new model
     *
     * @param array $attributes
     *
     * @return static
     * @throws EloquentException
     */
    public static function new(array $attributes = []): self
    {
        try {
            /* @var static $self */
            $self = bean(static::class);
        } catch (\Throwable $e) {
            throw new EloquentException($e->getMessage());
        }

        $self->syncOriginal();
        $self->fill($attributes);
        $self->exists = true;

        return $self;
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array $attributes
     *
     * @return static
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Qualify the given column name by the model's table.
     *
     * @param  string $column
     *
     * @return string
     * @throws EntityException
     */
    public function qualifyColumn(string $column): string
    {
        if (Str::contains($column, '.')) {
            return $column;
        }

        return $this->getTable() . '.' . $column;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool  $exists
     *
     * @return static
     * @throws EloquentException
     */
    public function newInstance(array $attributes = [], bool $exists = false): self
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the EloquentException query builder instances.
        $model = static::new($attributes);

        $model->exists = $exists;

        return $model;
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array $attributes
     *
     * @return static
     * @throws
     */
    public function newFromBuilder($attributes = [])
    {
        $model = $this->newInstance([], true);

        $model->setRawAttributes((array)$attributes, true);
        return $model;
    }

    /**
     * Get all of the models from the database.
     *
     * @param array $columns
     *
     * @return Collection
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public static function all(array $columns = ['*']): Collection
    {
        return static::new()->newQuery()->get($columns);
    }

    /**
     * Increment a column's value by a given amount.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     *
     * @return int
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    protected function increment(string $column, $amount = 1, array $extra = [])
    {
        return $this->incrementOrDecrement($column, $amount, $extra, 'increment');
    }

    /**
     * Decrement a column's value by a given amount.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     *
     * @return int
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    protected function decrement(string $column, $amount = 1, array $extra = [])
    {
        return $this->incrementOrDecrement($column, $amount, $extra, 'decrement');
    }

    /**
     * Run the increment or decrement method on the model.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     * @param  string    $method
     *
     * @return int
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    protected function incrementOrDecrement(string $column, $amount, array $extra, string $method)
    {
        $query = $this->newModelQuery();

        if (!$this->exists) {
            return $query->{$method}($column, $amount, $extra);
        }

        $this->incrementOrDecrementAttributeValue($column, $amount, $extra, $method);

        return $query->where(
            $this->getKeyName(), $this->getKey()
        )->{$method}($column, $amount, $extra);
    }

    /**
     * Increment the underlying attribute value and sync with original.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     * @param  string    $method
     *
     * @return void
     */
    protected function incrementOrDecrementAttributeValue(string $column, $amount, $extra, $method)
    {
        $this->{$column} = $this->{$column} + ($method === 'increment' ? $amount : $amount * -1);

        $this->fill($extra);

        $this->syncOriginalAttribute($column);
    }

    /**
     * Update the model in the database.
     *
     * @param array $attributes
     *
     * @return bool
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function update(array $attributes = [])
    {
        if (!$this->exists) {
            return false;
        }

        return $this->fill($attributes)->save();
    }

    /**
     * Save the model to the database.
     *
     * @return bool
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function save()
    {
        $query = $this->newModelQuery();

        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.

        // fire saving

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->isDirty() ?
                $this->performUpdate($query) : true;
        }

        // If the model is brand new, we'll insert it into our database and set the
        // ID attribute on the model to the value of the newly inserted row's ID
        // which is typically an auto-increment value managed by the database.
        else {
            $saved = $this->performInsert($query);
        }

        // If the model is successfully saved, we need to do a few more things once
        // that is done. We will call the "saved" method here to run any actions
        // we need to happen after a model gets successfully saved right here.
        if ($saved) {
            $this->finishSave();
        }

        return $saved;
    }

    /**
     * Save the model to the database using transaction.
     *
     * @param  array $options
     *
     * @return bool
     *
     * @throws \Throwable
     */
    public function saveOrFail(array $options = [])
    {
        return $this->getConnection()->transaction(function () use ($options) {
            return $this->save();
        });
    }

    /**
     * Perform any actions that are necessary after the model is saved.
     *
     * @return void
     */
    protected function finishSave()
    {
        // fire saved

        $this->syncOriginal();
    }

    /**
     * Perform a model update operation.
     *
     * @param  Builder $query
     *
     * @return bool
     * @throws EntityException
     */
    protected function performUpdate(Builder $query)
    {
        // fire updating

        // Once we have run the update operation, we will fire the "updated" event for
        // this model instance. This will allow developers to hook into these after
        // models are updated, giving them a chance to do any special processing.
        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            $this->setKeysForSaveQuery($query)->update($dirty);

            $this->syncChanges();

            // fire updated
        }

        return true;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  Builder $query
     *
     * @return Builder
     * @throws EntityException
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @return mixed
     * @throws EntityException
     */
    protected function getKeyForSaveQuery()
    {
        return $this->original[$this->getKeyName()]
            ?? $this->getKey();
    }

    /**
     * Perform a model insert operation.
     *
     * @param  Builder $query
     *
     * @return bool
     * @throws QueryException
     * @throws EntityException
     */
    protected function performInsert(Builder $query)
    {
        // fire creating

        // If the model has an incrementing key, we can use the "insertGetId" method on
        // the query builder, which will give us back the final inserted ID for this
        // table from the database. Not all tables have to be incrementing though.
        $attributes = $this->getAttributes();

        if ($this->getIncrementing()) {
            $this->insertAndSetId($query, $attributes);
        }

        // If the table isn't incrementing we'll simply insert these attributes as they
        // are. These attribute arrays must contain an "id" column previously placed
        // there by the developer as the manually determined key for these models.
        else {
            if (empty($attributes)) {
                return true;
            }

            $query->insert($attributes);
        }

        // We will go ahead and set the exists property to true, so that it is set when
        // the created event is fired, just in case the developer tries to update it
        // during the event. This will allow them to do so and run an update here.
        $this->exists = true;

        // fire created
        return true;
    }

    /**
     * Insert the given attributes and set the ID on the model.
     *
     * @param  Builder $query
     * @param  array   $attributes
     *
     * @return void
     * @throws QueryException
     * @throws EntityException
     */
    protected function insertAndSetId(Builder $query, $attributes)
    {
        $id = $query->insertGetId($attributes, $keyName = $this->getKeyName());

        $this->setAttribute($keyName, $id);
    }

    /**
     * Delete the model from the database.
     *
     * @return bool
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function delete(): bool
    {
        if (is_null($this->getKeyName())) {
            throw new EloquentException('No primary key defined on model.');
        }

        // If the model doesn't exist, there is nothing to delete so we'll just return
        // immediately and not do anything else. Otherwise, we will continue with a
        // deletion process on the model, firing the proper events, and so forth.
        if (!$this->exists) {
            return false;
        }

        // fire deleting

        $this->performDeleteOnModel();

        // Once the model has been deleted, we will fire off the deleted event so that
        // the developers may hook into post-delete operations. We will then return
        // a boolean true as the delete is presumably successful on the database.

        // fire deleted

        return true;
    }

    /**
     * Force a hard delete on a soft deleted model.
     *
     * This method protects developers from running forceDelete when trait is missing.
     *
     * @return bool
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function forceDelete()
    {
        return $this->delete();
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    protected function performDeleteOnModel()
    {
        $this->setKeysForSaveQuery($this->newModelQuery())->delete();

        $this->exists = false;
    }

    /**
     * Begin querying the model.
     *
     * @return Builder
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public static function query()
    {
        return static::new()->newQuery();
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return Builder
     * @return Builder
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function newQuery()
    {
        return $this->newModelQuery();
    }

    /**
     * Get a new query builder that doesn't have any global scopes or eager loading.
     *
     * @return Builder
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function newModelQuery()
    {
        return $this->newEloquentBuilder($this->newBaseQueryBuilder())->setModel($this);
    }

    /**
     * Create a new EloquentException query builder for the model.
     *
     * @param  QueryBuilder $query
     *
     * @return Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return QueryBuilder
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        return QueryBuilder::new($connection, $connection->getQueryGrammar(), $connection->getPostProcessor());
    }

    /**
     * Create a new EloquentException Collection instance.
     *
     * @param  array $models
     *
     * @return Collection
     * @throws PrototypeException
     */
    public function newCollection(array $models = [])
    {
        return Collection::new($models);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return JsonHelper::encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Reload a fresh model instance from the database.
     *
     * @return static
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function fresh()
    {
        if (!$this->exists) {
            return $this;
        }

        return $this->newModelQuery()
            ->where($this->getKeyName(), $this->getKey())
            ->first();
    }

    /**
     * Reload the current model instance with fresh attributes from the database.
     *
     * @return $this
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function refresh()
    {
        if (!$this->exists) {
            return $this;
        }

        $this->setRawAttributes(
            $this->newModelQuery()->findOrFail($this->getKey())->attributes
        );

        $this->syncOriginal();

        return $this;
    }

    /**
     * Determine if two models have the same ID and belong to the same table.
     *
     * @param  Model $model
     *
     * @return bool
     * @throws EntityException
     */
    public function is(Model $model): bool
    {
        return !is_null($model) &&
            $this->getKey() === $model->getKey() &&
            $this->getTable() === $model->getTable();
    }

    /**
     * Determine if two models are not the same.
     *
     * @param  Model $model
     *
     * @return bool
     * @throws EntityException
     */
    public function isNot(Model $model): bool
    {
        return !$this->is($model);
    }

    /**
     * Get the database connection for the model.
     *
     * @throws EntityException
     * @throws PoolException
     */
    public function getConnection(): Connection
    {
        $pool = EntityRegister::getPool(static::class);

        return DB::pool($pool);
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     * @throws EntityException
     */
    public function getTable()
    {
        return EntityRegister::getTable(static::class);
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     * @throws EntityException
     */
    public function getKeyName()
    {
        return EntityRegister::getId(static::class);
    }

    /**
     * Get the table qualified key name.
     *
     * @return string
     * @throws EntityException
     */
    public function getQualifiedKeyName()
    {
        return $this->qualifyColumn($this->getKeyName());
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return EntityRegister::getIdIncrementing(static::class);
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     * @throws EntityException
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     * @throws EntityException
     */
    public function getRouteKeyName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set the number of models to return per page.
     *
     * @param  int $perPage
     *
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * Forward a method call to the given object.
     *
     * @param  mixed  $object
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    protected function forwardCallTo($object, $method, $parameters)
    {
        try {
            return $object->{$method}(...$parameters);
        } catch (\Exception | \BadMethodCallException $e) {
            $pattern = '~^Call to undefined method (?P<class>[^:]+)::(?P<method>[^\(]+)\(\)$~';

            if (!preg_match($pattern, $e->getMessage(), $matches)) {
                throw $e;
            }

            if ($matches['class'] != get_class($object) ||
                $matches['method'] != $method) {
                throw $e;
            }

            throw new \BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()', static::class, $method
            ));
        }
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}