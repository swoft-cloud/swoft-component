<?php declare(strict_types=1);

namespace SwoftTest\Validator\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class RequiredValidator
 * @Validator()
 */
class RequiredValidator
{
    /**
     * @Required()
     * @IsString()
     */
    protected $required;
}
