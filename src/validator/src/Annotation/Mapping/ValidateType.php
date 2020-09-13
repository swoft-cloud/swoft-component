<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Validator\Annotation\Mapping;

/**
 * Class ValidateType
 *
 * @since 2.0
 */
class ValidateType
{
    /**
     * Get query params
     */
    public const GET = 'get';

    /**
     * Post form and body
     */
    public const BODY = 'body';

    /**
     * Route path params
     */
    public const PATH = 'path';
}
