<?php declare(strict_types=1);


namespace Swoft\Db;

use mysql_xdevapi\Exception;
use Swoft\Db\Exception\EntityException;

/**
 * Class EntityRegister
 *
 * @since 2.0
 */
class EntityRegister
{
    /**
     * Entity array
     *
     * @var array
     *
     * @example
     * [
     *     'entityClassName' => [
     *         'table' => 'table',
     *         'pool' => 'pool',
     *         'id' => 'attrName'
     *     ]
     * ]
     */
    private static $entity = [];

    /**
     * Columns
     *
     * @var array
     *
     * @example
     * [
     *     'entityClassName' =>[
     *         'mapping' => [
     *             'attrName' => [
     *                 'column' => 'columnName',
     *                 'pro' => 'proName',
     *                 'hidden' => false,
     *             ]
     *         ],
     *         'reverse' => [
     *             'columnName' => [
     *                 'attr' => 'attrName',
     *                 'pro' => 'proName',
     *                 'hidden' => false,
     *             ]
     *         ]
     *     ]
     * ]
     */
    private static $columns = [];

    /**
     * Register `Entity`
     *
     * @param string $className
     * @param string $table
     * @param string $pool
     */
    public static function registerEntity(string $className, string $table, string $pool): void
    {
        self::$entity[$className] = [
            'table' => $table,
            'pool'  => $pool,
        ];
    }

    /**
     * Register `Id`
     *
     * @param string $className
     * @param string $attrName
     *
     * @throws EntityException
     */
    public static function registerId(string $className, string $attrName): void
    {
        if (!isset(self::$entity[$className])) {
            throw new EntityException(sprintf('%s must be `@Entity` to use `@Id`', $className));
        }

        self::$entity[$className]['id'] = $attrName;
    }

    /**
     * Register `Column`
     *
     * @param string $className
     * @param string $attrName
     * @param string $column
     * @param string $pro
     * @param bool   $hidden
     *
     * @throws EntityException
     */
    public static function registerColumn(
        string $className,
        string $attrName,
        string $column,
        string $pro,
        bool $hidden
    ): void {
        if (!isset(self::$entity[$className])) {
            throw new EntityException(sprintf('%s must be `@Entity` to use `@Column`', $className));
        }

        if (isset(self::$columns[$className]['reverse'][$column])) {
            throw new EntityException(sprintf('The `%s` name of `@Column` has exist in %s', $className, $column));
        }

        self::$columns[$className]['mapping'][$attrName] = [
            'column' => $column,
            'pro'    => $pro,
            'hidden' => $hidden,
        ];

        self::$columns[$className]['reverse'][$column] = [
            'attr'   => $attrName,
            'pro'    => $pro,
            'hidden' => $hidden,
        ];
    }

    /**
     * Get table
     *
     * @param string $className
     *
     * @return string
     * @throws EntityException
     */
    public static function getTable(string $className): string
    {
        $table = self::$entity[$className]['table'] ?? '';
        if (empty($table)) {
            throw new EntityException(sprintf('The table of `%s` entity is not defined or empty', $className));
        }

        return $table;
    }

    /**
     * Get pool
     *
     * @param string $className
     *
     * @return string
     * @throws EntityException
     */
    public static function getPool(string $className): string
    {
        $pool = self::$entity[$className]['pool'] ?? '';
        if (empty($pool)) {
            throw new EntityException(sprintf('The pool of `%s` entity is not defined or empty', $className));
        }

        return $pool;
    }

    /**
     * Get pk id
     *
     * @param string $className
     *
     * @return string
     * @throws EntityException
     */
    public static function getId(string $className): string
    {
        $idAttrName = self::$entity[$className]['id'] ?? '';
        if (empty($idAttrName)) {
            throw new EntityException(sprintf('The `@Id` of `%s` entity is not defined', $className));
        }

        $idColumn = self::$columns[$className]['mapping'][$idAttrName]['column'] ?? '';
        if (empty($idColumn)) {
            throw new EntityException(
                sprintf('`%s` property must be define `@Column` in %s', $idAttrName, $className)
            );
        }

        return $idColumn;
    }
}