<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class UserBaseValidate
 *
 * @since 2.0
 *
 * @Validator()
 */
class UserBaseValidate
{
    /**
     * @IsInt()
     *
     * @var int
     */
    protected $start;

    /**
     * @IsInt()
     *
     * @var int
     */
    protected $end;
}
