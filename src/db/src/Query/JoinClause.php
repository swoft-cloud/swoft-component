<?php declare(strict_types=1);


namespace Swoft\Db\Query;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\Prototype;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Bean\PrototypeInterface;
use Swoft\Db\Exception\QueryException;

/**
 * Class JoinClause
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class JoinClause extends Builder implements PrototypeInterface
{
    use Prototype;

    /**
     * The type of join being performed.
     *
     * @var string
     */
    public $type;

    /**
     * The table the join clause is joining to.
     *
     * @var string
     */
    public $table;

    /**
     * The parent query builder instance.
     *
     * @var Builder
     */
    private $parentQuery;

    /**
     * Create a new join clause instance.
     *
     * @param Builder $parentQuery
     * @param string  $type
     * @param string  $table
     */
    public function initializeJoinClause(Builder $parentQuery, string $type, string $table)
    {
        $this->type        = $type;
        $this->table       = $table;
        $this->parentQuery = $parentQuery;

        $connection = $parentQuery->getConnection();
        $grammar    = $parentQuery->getGrammar();
        $processor  = $parentQuery->getProcessor();

        $this->connection = $connection;
        $this->grammar    = $grammar ?: $connection->getQueryGrammar();
        $this->processor  = $processor ?: $connection->getPostProcessor();
    }

    /**
     * @param mixed ...$params
     *
     * @return JoinClause
     * @throws PrototypeException
     */
    public static function new(...$params): self
    {
        /**
         * @var Builder $parentQuery
         * @var string  $type
         * @var string  $table
         */
        list($parentQuery, $type, $table) = $params;

        $self = self::__instance();

        $self->type        = $type;
        $self->table       = $table;
        $self->parentQuery = $parentQuery;

        $connection = $parentQuery->getConnection();
        $grammar    = $parentQuery->getGrammar();
        $processor  = $parentQuery->getProcessor();

        $self->connection = $connection;
        $self->grammar    = $grammar ?: $connection->getQueryGrammar();
        $self->processor  = $processor ?: $connection->getPostProcessor();
        return $self;
    }


    /**
     * Add an "on" clause to the join.
     *
     * On clauses can be chained, e.g.
     *
     *  $join->on('contacts.user_id', '=', 'users.id')
     *       ->on('contacts.info_id', '=', 'info.id')
     *
     * will produce the following SQL:
     *
     * on `contacts`.`user_id` = `users`.`id`  and `contacts`.`info_id` = `info`.`id`
     *
     * @param  \Closure|string $first
     * @param  string|null     $operator
     * @param  string|null     $second
     * @param  string          $boolean
     *
     * @return $this
     *
     * @throws QueryException
     */
    public function on($first, $operator = null, $second = null, $boolean = 'and')
    {
        if ($first instanceof \Closure) {
            return $this->whereNested($first, $boolean);
        }

        return $this->whereColumn($first, $operator, $second, $boolean);
    }

    /**
     * Add an "or on" clause to the join.
     *
     * @param  \Closure|string $first
     * @param  string|null     $operator
     * @param  string|null     $second
     *
     * @return static
     * @throws QueryException
     */
    public function orOn($first, $operator = null, $second = null)
    {
        return $this->on($first, $operator, $second, 'or');
    }

    /**
     * Get a new instance of the join clause builder.
     *
     * @return static
     * @throws PrototypeException
     */
    public function newQuery(): Builder
    {
        return JoinClause::new($this->parentQuery, $this->type, $this->table);
    }

    /**
     * Create a new query instance for sub-query.
     *
     * @return Builder
     * @throws PrototypeException
     */
    protected function forSubQuery(): Builder
    {
        return $this->parentQuery->newQuery();
    }
}