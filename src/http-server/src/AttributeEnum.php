<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
