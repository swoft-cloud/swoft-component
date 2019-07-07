<?php declare(strict_types=1);


namespace Swoft\Db\Concern;

use Swoft\Db\EntityRegister;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Schema\Grammars\Grammar;
use function time;
use function date;
use function is_null;

/**
 * Trait HasTimestamps
 *
 * @since 2.0
 */
trait HasTimestamps
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $modelTimestamps = false;

    /**
     * Write date format
     *
     * @var string
     */
    public $modelDateFormat = 'Y-m-d H:i:s';

    /**
     * Update the model's update timestamp.
     *
     * @return bool
     * @throws DbException
     */
    public function touch(): bool
    {
        if (!$this->usesTimestamps()) {
            return false;
        }

        $this->updateTimestamps();

        return $this->save();
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return void
     * @throws DbException
     */
    protected function updateTimestamps(): void
    {
        $time = $this->freshTimestamp();

        if (!is_null(static::UPDATED_AT) && !$this->isDirty(static::UPDATED_AT)) {
            $this->setModelAttribute(static::UPDATED_AT, $time);
        }

        if (!$this->swoftExists && !is_null(static::CREATED_AT)
            && !$this->isDirty(static::CREATED_AT)) {
            $this->setModelAttribute(static::CREATED_AT, $time);
        }
    }

    /**
     * Get a fresh timestamp for the model.
     *
     * @return int|string
     * @throws DbException
     */
    public function freshTimestamp()
    {
        if (!is_null(static::WRITE_TIMESTAMP_TYPE)) {
            return static::WRITE_TIMESTAMP_TYPE === static::DATE_TYPE ? date($this->modelDateFormat) : time();
        }

        // Auto choose timestamp type
        $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), static::CREATED_AT);
        if (empty($mapping)) {
            $mapping = EntityRegister::getReverseMappingByColumn($this->getClassName(), static::UPDATED_AT);
        }

        if (empty($mapping)) {
            throw new DbException(sprintf('Column(%s) is not exist!',
                static::CREATED_AT . ' or ' . static::UPDATED_AT));
        }

        return $mapping['type'] === Grammar::STRING ? date($this->modelDateFormat) : time();
    }

    /**
     * Determine if the model uses timestamps.
     *
     * @return bool
     */
    public function usesTimestamps(): bool
    {
        return $this->modelTimestamps;
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string
     */
    public function getCreatedAtColumn()
    {
        return static::CREATED_AT;
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string
     */
    public function getUpdatedAtColumn()
    {
        return static::UPDATED_AT;
    }
}
