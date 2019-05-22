<?php declare(strict_types=1);


namespace Swoft\Db\Contract;

/**
 * Class MigrationInterface
 *
 * @since 2.0
 */
interface MigrationInterface
{
    /**
     * @return bool
     */
    public function up(): bool;

    /**
     * @return bool
     */
    public function down(): bool;
}