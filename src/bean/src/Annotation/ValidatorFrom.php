<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Annotation;

class ValidatorFrom
{
    /**
     * get params
     */
    const GET = 'get';

    /**
     * post params
     */
    const POST = 'post';

    /**
     * path params
     */
    const PATH = 'path';

    /**
     * get/post/path params
     */
    const QUERY = 'query';

    /**
     * the property of entity
     */
    const PROPERTY = 'property';

    /**
     * rpc args
     */
    const SERVICE = 'service';
}
