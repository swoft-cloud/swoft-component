<?php declare(strict_types=1);


namespace Swoft\Db;

/**
 * Class DbEvent
 *
 * @since 2.0
 */
class DbEvent
{
    /**
     * Begin transaction
     */
    public const BEGIN_TRANSACTION = 'swoft.db.transaction.begin';

    /**
     * Commit transaction
     */
    public const COMMIT_TRANSACTION = 'swoft.db.transaction.commit';

    /**
     * Rollback
     */
    public const ROLLBACK_TRANSACTION = 'swoft.db.transaction.rollback';
}