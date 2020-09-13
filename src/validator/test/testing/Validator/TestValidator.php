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

use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;

/**
 * Class TestValidator
 *
 * @since 2.0
 *
 * @Validator()
 */
class TestValidator
{
    /**
     * @IsArray()
     * @Required()
     *
     * @var array
     */
    protected $array;

    /**
     * @IsString()
     * @Required()
     *
     * @var string
     */
    protected $string;

    /**
     * @IsInt()
     * @Required()
     *
     * @var int
     */
    protected $int;

    /**
     * @IsBool()
     * @Required()
     *
     * @var int
     */
    protected $bool;

    /**
     * @IsFloat()
     * @Required()
     *
     * @var int
     */
    protected $float;

    /**
     * @IsArray(message="array message")
     * @Required()
     *
     * @var array
     */
    protected $arrayMessage;

    /**
     * @IsString(message="string message")
     * @Required()
     *
     * @var string
     */
    protected $stringMessage;

    /**
     * @IsInt(message="int message")
     * @Required()
     *
     * @var int
     */
    protected $intMessage;

    /**
     * @IsBool(message="bool message")
     * @Required()
     *
     * @var int
     */
    protected $boolMessage;

    /**
     * @IsFloat(message="float message")
     * @Required()
     *
     * @var int
     */
    protected $floatMessage;

    /**
     * @IsArray()
     *
     * @var array
     */
    protected $arrayDefault = [];

    /**
     * @IsString()
     *
     * @var string
     */
    protected $stringDefault = '';

    /**
     * @IsInt()
     *
     * @var int
     */
    protected $intDefault = 6;

    /**
     * @IsBool()
     *
     * @var int
     */
    protected $boolDefault = false;

    /**
     * @IsFloat()
     *
     * @var int
     */
    protected $floatDefault = 1.0;

    /**
     * @IsString(name="swoftName")
     *
     * @var string
     */
    protected $name = 'swoft';
}
