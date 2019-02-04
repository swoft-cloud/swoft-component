<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 13:48
 */

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\Request;

/**
 * Class Connection
 * @package Swoft\WebSocket\Server
 * @since 2.0
 * @Bean(scope=Bean::REQUEST")
 */
class Connection extends AbstractContext
{
    /**
     * @var Request
     */
    private $request;

}
