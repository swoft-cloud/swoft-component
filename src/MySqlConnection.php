<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Query\Grammar\MySqlGrammar;
use Swoft\Db\Query\Grammar\Grammar;

/**
 * Class MySqlConnection
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class MySqlConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return MySqlGrammar
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function getDefaultQueryGrammar(): Grammar
    {
        $grammar = \bean(MySqlGrammar::class);
        if (!$grammar instanceof MySqlGrammar) {
            throw new \InvalidArgumentException('%s class is not Grammar instance', get_class($grammar));
        }
        return $this->withTablePrefix($grammar);
    }

    /**
     * Set the table prefix and return the grammar.
     *
     * @param Grammar $grammar
     *
     * @return Grammar
     */
    public function withTablePrefix(Grammar $grammar)
    {
        $grammar->setTablePrefix($this->database->getPrefix());

        return $grammar;
    }
}