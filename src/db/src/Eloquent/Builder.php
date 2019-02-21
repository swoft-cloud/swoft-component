<?php declare(strict_types=1);


namespace Swoft\Db\Eloquent;


use Swoft\Bean\Exception\PrototypeException;
use Swoft\Db\Concern\BuildsQueries;
use Swoft\Db\Exception\EloquentException;
use Swoft\Db\Exception\EntityException;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
use Swoft\Db\Query\Builder as QueryBuilder;
use Swoft\Stdlib\Arrayable;
use Swoft\Stdlib\Helper\Arr;

/**
 * Class Builder
 *
 * @mixin \Swoft\Db\Query\Builder
 *
 * @since 2.0
 */
class Builder
{
    use BuildsQueries;

    /**
     * The base query builder instance.
     *
     * @var QueryBuilder
     */
    protected $query;

    /**
     * The model being queried.
     *
     * @var Model
     */
    protected $model;

    /**
     * All of the globally registered builder macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * All of the locally registered builder macros.
     *
     * @var array
     */
    protected $localMacros = [];

    /**
     * A replacement for the typical delete function.
     *
     * @var \Closure
     */
    protected $onDelete;

    /**
     * The methods that should be returned from query builder.
     *
     * @var array
     */
    protected $passthru = [
        'insert',
        'insertGetId',
        'getBindings',
        'toSql',
        'exists',
        'doesntExist',
        'count',
        'min',
        'max',
        'avg',
        'average',
        'sum',
        'getConnection',
    ];

    /**
     * Create a new EloquentException query builder instance.
     *
     * @param  QueryBuilder $query
     *
     * @return void
     */
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    /**
     * Create and return an un-saved model instance.
     *
     * @param  array $attributes
     *
     * @return Model
     * @throws EloquentException
     */
    public function make(array $attributes = [])
    {
        return $this->newModelInstance($attributes);
    }

    /**
     * Add a where clause on the primary key to the query.
     *
     * @param $id
     *
     * @return $this|Builder
     * @throws EntityException
     * @throws PrototypeException
     * @throws PoolException
     */
    public function whereKey($id)
    {
        if (is_array($id) || $id instanceof Arrayable) {
            $this->query->whereIn($this->model->getQualifiedKeyName(), $id);

            return $this;
        }

        return $this->where($this->model->getQualifiedKeyName(), '=', $id);
    }

    /**
     * @param $id
     *
     * @return $this|Builder
     * @throws EntityException
     * @throws PrototypeException
     * @throws PoolException
     */
    public function whereKeyNot($id)
    {
        if (is_array($id) || $id instanceof Arrayable) {
            $this->query->whereNotIn($this->model->getQualifiedKeyName(), $id);

            return $this;
        }

        return $this->where($this->model->getQualifiedKeyName(), '!=', $id);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure $column
     * @param  mixed                 $operator
     * @param  mixed                 $value
     * @param  string                $boolean
     *
     * @return $this
     * @throws EntityException
     * @throws PrototypeException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function where($column, $operator = null, $value = null, string $boolean = 'and')
    {
        if ($column instanceof \Closure) {
            $column($query = $this->model->newModelQuery());

            $this->query->addNestedWhereQuery($query->getQuery(), $boolean);
        } else {
            $this->query->where(...func_get_args());
        }

        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param  \Closure|array|string $column
     * @param  mixed                 $operator
     * @param  mixed                 $value
     *
     * @return $this|static
     * @throws EntityException
     * @throws PrototypeException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        [$value, $operator] = $this->query->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        return $this->where($column, $operator, $value, 'or');
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string $column
     *
     * @return $this
     */
    public function latest($column = null)
    {
        $this->query->latest($column);

        return $this;
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string $column
     *
     * @return $this
     */
    public function oldest($column = null)
    {
        $this->query->oldest($column);

        return $this;
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param  array $items
     *
     * @return Collection
     * @throws EloquentException
     * @throws PrototypeException
     */
    public function hydrate(array $items): Collection
    {
        $instance = $this->newModelInstance();

        return $instance->newCollection(array_map(function ($item) use ($instance) {
            return $instance->newFromBuilder($item);
        }, $items));
    }

    /**
     * Create a collection of models from a raw query.
     *
     * @param  string $query
     * @param  array  $bindings
     *
     * @return Collection
     * @throws EloquentException
     * @throws QueryException
     * @throws PrototypeException
     */
    public function fromQuery($query, $bindings = [])
    {
        return $this->hydrate(
            $this->query->getConnection()->select($query, $bindings)
        );
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return Model|Collection|static[]|static|null
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function find($id, $columns = ['*'])
    {
        if (is_array($id) || $id instanceof Arrayable) {
            return $this->findMany($id, $columns);
        }

        return $this->whereKey($id)->first($columns);
    }

    /**
     * Find multiple models by their primary keys.
     *
     * @param  Arrayable|array $ids
     * @param  array           $columns
     *
     * @return Collection
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function findMany($ids, $columns = ['*'])
    {
        if (empty($ids)) {
            return $this->model->newCollection();
        }

        return $this->whereKey($ids)->get($columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return Model|Collection|static|static[]
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->find($id, $columns);

        if (is_array($id)) {
            if (count($result) === count(array_unique($id))) {
                return $result;
            }
        } elseif (!is_null($result)) {
            return $result;
        }

        throw new EntityException('Model is not finded');
    }

    /**
     * Find a model by its primary key or return fresh model instance.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return Model|static
     * @throws EloquentException
     * @throws EntityException
     * @throws PrototypeException
     * @throws PoolException
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        if (!is_null($model = $this->find($id, $columns))) {
            return $model;
        }

        return $this->newModelInstance();
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array $attributes
     * @param  array $values
     *
     * @return Model|static
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        if (!is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        return $this->newModelInstance($attributes + $values);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array $attributes
     * @param  array $values
     *
     * @return Model|static
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        if (!is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        $instance = $this->newModelInstance($attributes + $values);
        $instance->save();

        return $instance;
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array $attributes
     * @param  array $values
     *
     * @return Model|static
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $instance = $this->firstOrNew($attributes);
        $instance->fill($values)->save();
        return $instance;
    }

    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param  array $columns
     *
     * @return Model|static
     *
     * @throws EntityException
     */
    public function firstOrFail($columns = ['*'])
    {
        if (!is_null($model = $this->first($columns))) {
            return $model;
        }

        throw new EntityException('Model is not find');
    }

    /**
     * Execute the query and get the first result or call a callback.
     *
     * @param  \Closure|array $columns
     * @param  \Closure|null  $callback
     *
     * @return Model|static|mixed
     */
    public function firstOr($columns = ['*'], \Closure $callback = null)
    {
        if ($columns instanceof \Closure) {
            $callback = $columns;

            $columns = ['*'];
        }

        if (!is_null($model = $this->first($columns))) {
            return $model;
        }

        return call_user_func($callback);
    }

    /**
     * Get a single column's value from the first result of a query.
     *
     * @param  string $column
     *
     * @return mixed
     */
    public function value(string $column)
    {
        if ($result = $this->first([$column])) {
            return $result->{$column};
        }
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array $columns
     *
     * @return Collection
     * @throws PrototypeException
     * @throws EloquentException
     * @throws PrototypeException
     */
    public function get($columns = ['*'])
    {
        $builder = $this;

        $models = $builder->getModels($columns);
        return $builder->getModel()->newCollection($models);
    }

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array $columns
     *
     * @return Model[]|static[]
     * @throws EloquentException
     * @throws PrototypeException
     */
    public function getModels($columns = ['*'])
    {
        return $this->model->hydrate(
            $this->query->get($columns)->all()
        )->all();
    }

    /**
     * Get a generator for the given query.
     *
     * @return \Generator
     * @throws QueryException
     */
    public function cursor()
    {
        foreach ($this->query->cursor() as $record) {
            yield $this->model->newFromBuilder($record);
        }
    }

    /**
     * Chunk the results of a query by comparing numeric IDs.
     *
     * @param  int         $count
     * @param  callable    $callback
     * @param  string|null $column
     * @param  string|null $alias
     *
     * @return bool
     * @throws EloquentException
     * @throws EntityException
     * @throws PrototypeException
     */
    public function chunkById($count, callable $callback, $column = null, $alias = null)
    {
        $column = is_null($column) ? $this->getModel()->getKeyName() : $column;

        $alias = is_null($alias) ? $column : $alias;

        $lastId = null;

        do {
            $clone = clone $this;

            // We'll execute the query for the given page and get the results. If there are
            // no results we can just break and return from here. When there are results
            // we will call the callback with the current chunk of these results here.
            $results = $clone->forPageAfterId($count, $lastId, $column)->get();

            $countResults = $results->count();

            if ($countResults == 0) {
                break;
            }

            // On each chunk result set, we will pass them to the callback and then let the
            // developer take care of everything within the callback, which allows us to
            // keep the memory low for spinning through large result sets for working.
            if ($callback($results) === false) {
                return false;
            }

            $lastId = $results->last()->{$alias};

            unset($results);
        } while ($countResults == $count);

        return true;
    }

    /**
     * Add a generic "order by" clause if the query doesn't already have one.
     *
     * @return void
     * @throws EntityException
     */
    protected function enforceOrderBy()
    {
        if (empty($this->query->orders) && empty($this->query->unionOrders)) {
            $this->orderBy($this->model->getQualifiedKeyName(), 'asc');
        }
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param  string      $column
     * @param  string|null $key
     *
     * @return \Swoft\Stdlib\Collection
     */
    public function pluck($column, $key = null)
    {
        $results = $this->toBase()->pluck($column, $key);

        // If the model has a mutator for the requested column, we will spin through
        // the results and mutate the values so that the mutated version of these
        // columns are returned as you would expect from these EloquentException models.
        if (!$this->model->hasGetMutator($column) &&
            !$this->model->hasCast($column) &&
            !in_array($column, $this->model->getDates())) {
            return $results;
        }

        return $results->map(function ($value) use ($column) {
            return $this->model->newFromBuilder([$column => $value])->{$column};
        });
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return Model|$this
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function create(array $attributes = [])
    {
        $instance = $this->newModelInstance($attributes);
        $instance->save();
        return $instance;
    }

    /**
     * Save a new model and return the instance. Allow mass-assignment.
     *
     * @param  array $attributes
     *
     * @return Model|$this
     * @throws EloquentException
     * @throws EntityException
     * @throws PoolException
     * @throws PrototypeException
     * @throws QueryException
     */
    public function forceCreate(array $attributes)
    {
        return $this->newModelInstance()->create($attributes);
    }

    /**
     * Update a record in the database.
     *
     * @param  array $values
     *
     * @return int
     * @throws QueryException
     */
    public function update(array $values)
    {
        return $this->toBase()->update($values);
    }

    /**
     * Increment a column's value by a given amount.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     *
     * @return int
     * @throws QueryException
     */
    public function increment($column, $amount = 1, array $extra = [])
    {
        return $this->toBase()->increment(
            $column, $amount, $extra
        );
    }

    /**
     * Decrement a column's value by a given amount.
     *
     * @param  string    $column
     * @param  float|int $amount
     * @param  array     $extra
     *
     * @return int
     * @throws QueryException
     */
    public function decrement($column, $amount = 1, array $extra = [])
    {
        return $this->toBase()->decrement(
            $column, $amount, $extra
        );
    }

    /**
     * Delete a record from the database.
     *
     * @return mixed
     * @throws PrototypeException
     * @throws QueryException
     */
    public function delete()
    {
        if (isset($this->onDelete)) {
            return call_user_func($this->onDelete, $this);
        }

        return $this->toBase()->delete();
    }

    /**
     * Run the default delete function on the builder.
     *
     * Since we do not apply scopes here, the row will actually be deleted.
     *
     * @return mixed
     * @throws PrototypeException
     * @throws QueryException
     */
    public function forceDelete()
    {
        return $this->query->delete();
    }

    /**
     * Register a replacement for the default delete function.
     *
     * @param  \Closure $callback
     *
     * @return void
     */
    public function onDelete(\Closure $callback)
    {
        $this->onDelete = $callback;
    }

    /**
     * Create a new instance of the model being queried.
     *
     * @param array $attributes
     *
     * @return Model
     * @throws \Swoft\Db\Exception\EloquentException
     */
    public function newModelInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Get the underlying query builder instance.
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the underlying query builder instance.
     *
     * @param  QueryBuilder $query
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get a base query builder instance.
     *
     * @return QueryBuilder
     */
    public function toBase()
    {
        return $this->getQuery();
    }

    /**
     * Get the model instance being queried.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set a model instance for the model being queried.
     *
     * @param  Model $model
     *
     * @return $this
     * @throws EntityException
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->query->from($model->getTable());

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
    public function qualifyColumn($column)
    {
        return $this->model->qualifyColumn($column);
    }

    /**
     * Get the given macro by name.
     *
     * @param  string $name
     *
     * @return \Closure
     */
    public function getMacro($name)
    {
        return Arr::get($this->localMacros, $name);
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($method === 'macro') {
            $this->localMacros[$parameters[0]] = $parameters[1];

            return;
        }

        if (isset($this->localMacros[$method])) {
            array_unshift($parameters, $this);

            return $this->localMacros[$method](...$parameters);
        }

        if (isset(static::$macros[$method])) {
            if (static::$macros[$method] instanceof \Closure) {
                return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
            }

            return call_user_func_array(static::$macros[$method], $parameters);
        }

        if (in_array($method, $this->passthru)) {
            return $this->toBase()->{$method}(...$parameters);
        }

        $this->forwardCallTo($this->query, $method, $parameters);

        return $this;
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if ($method === 'macro') {
            static::$macros[$parameters[0]] = $parameters[1];

            return;
        }

        if (!isset(static::$macros[$method])) {
            static::throwBadMethodCallException($method);
        }

        if (static::$macros[$method] instanceof \Closure) {
            return call_user_func_array(\Closure::bind(static::$macros[$method], null, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    /**
     * Force a clone of the underlying query builder when cloning.
     *
     * @return void
     */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}