<?php declare(strict_types=1);


namespace Swoft\Db\Schema\Grammars;

use Swoft\Db\Grammar as BaseGrammar;

/**
 * Class Grammar
 *
 * @since 2.0
 */
abstract class Grammar extends BaseGrammar
{

    /**
     * The commands to be executed outside of create or alter command.
     *
     * @var array
     */
    protected $commands;

    /**
     * Get the commands for the grammar.
     *
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Compile the query to determine if a table exists.
     *
     * @return string
     */
    abstract public function compileTableExists(): string;

    /**
     * Compile the query to determine the list of columns.
     *
     * @return string
     */
    abstract public function compileColumnListing(): string;

    /**
     * Compile the command to enable foreign key constraints.
     *
     * @return string
     */
    abstract public function compileEnableForeignKeyConstraints(): string;

    /**
     * Compile the command to disable foreign key constraints.
     *
     * @return string
     */
    abstract public function compileDisableForeignKeyConstraints();
}
