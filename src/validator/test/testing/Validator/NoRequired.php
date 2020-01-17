<?php declare(strict_types=1);

namespace SwoftTest\Validator\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class NoRequired
 *
 * @since 2.0
 *
 * @Validator()
 */
class NoRequired
{
    /**
     * @IsInt()
     *
     * @var int
     */
    protected $int;
}
