<?php declare(strict_types=1);


namespace Swoft\Db\Annotation\Mapping;

/**
 * Class Entity
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("table", type="string"),
 *     @Attribute("connection", type="string"),
 * })
 *
 * @since 2.0
 */
class Entity
{
    /**
     * Table name
     *
     * @var string
     */
    private $table = '';

    /**
     * Deafaut
     *
     * @var string
     */
    private $connection = '';

    /**
     * Entity constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->table = $values['value'];
        }
        if (isset($values['table'])) {
            $this->table = $values['table'];
        }
        if (isset($values['connection'])) {
            $this->connection = $values['connection'];
        }
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }
}