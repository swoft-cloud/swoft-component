<?php
namespace Swoft\Http\Server;

/**
 * Class AttributeEnum
 */
class AttributeEnum
{
    /**
     * The attribute of Router
     *
     * @var string
     */
    const ROUTER_ATTRIBUTE = 'requestHandler';

    /** @var string The matched params key of the router */
    const ROUTER_PARAMS = '_params';

    /**
     * The attribute of response data
     *
     * @var string
     */
    const RESPONSE_ATTRIBUTE = 'responseAttribute';

}
