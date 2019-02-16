<?php

use Swoft\Db\Query\Expression;
use Swoft\Db\Query\Builder;
use Swoft\Db\Connection;
use Swoft\Db\Query\Grammar\Grammar;
use Swoft\Db\Query\Processor\Processor;
use Swoft\Db\Exception\QueryException;
use Swoft\Db\Query\JoinClause;

if (!function_exists('expression')) {
    /**
     * New a expression
     *
     * @param mixed $value
     *
     * @return Expression
     * @throws QueryException
     */
    function expression($value): Expression
    {
        try {
            /* @var Expression $expression */
            $expression = bean(Expression::class);
            $expression->initialize($value);
        } catch (Throwable $e) {
            throw new QueryException('New `Expression` error is %s', $e->getMessage());
        }

        return $expression;
    }
}

if (!function_exists('builder')) {
    /**
     * New a buidler
     *
     * @param Connection     $connection
     * @param Grammar|null   $grammar
     * @param Processor|null $processor
     *
     * @return Builder
     * @throws QueryException
     */
    function builder(Connection $connection, Grammar $grammar = null, Processor $processor = null): Builder
    {
        try {
            /* @var Builder $builder */
            $builder = bean(Builder::class);
            $builder->initialize($connection, $grammar, $processor);
        } catch (Throwable $e) {
            throw new QueryException('New `Builder` error is %s', $e->getMessage());
        }

        return $builder;
    }
}

if (!function_exists('join_clause')) {
    /**
     * New a join clause
     *
     * @param Builder $parentQuery
     * @param string  $type
     * @param string  $table
     *
     * @return JoinClause
     * @throws QueryException
     */
    function join_clause(Builder $parentQuery, string $type, string $table): JoinClause
    {
        try {
            /* @var Builder $builder */
            $joinClause = bean(JoinClause::class);
            $joinClause->initializeJoinClause($parentQuery, $type, $table);
        } catch (Throwable $e) {
            throw new QueryException('New `JoinClause` error is %s', $e->getMessage());
        }

        return $joinClause;
    }
}

