<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class TransactionManager
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST)
 */
class TransactionManager
{
    public function get(){
        var_dump('TransactionManager');
    }
}