<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Query\Grammar\MySqlGrammar;

/**
 * Class MySqlConnection
 *
 * @Bean(scope=Bean::PROTOTYPE)
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
    protected function getDefaultQueryGrammar()
    {
        $grammar = \bean(MySqlGrammar::class);
        if(!$grammar instanceof MySqlGrammar){
            
        }
        return $this->withTablePrefix($grammar);
    }

    /**
     * Set the table prefix and return the grammar.
     *
     * @param MySqlGrammar $grammar
     *
     * @return MySqlGrammar
     */
    public function withTablePrefix(MySqlGrammar $grammar)
    {
        $grammar->setTablePrefix($this->database->getPrefix());

        return $grammar;
    }
}