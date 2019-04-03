<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Annotation\Mapping;


use Swoft\Rpc\Protocol;

class Service
{
    /**
     * @var string
     */
    private $version = Protocol::DEFAULT_VERSION;
}