<?php declare(strict_types=1);


namespace SwoftTest\Validator\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\IntType;
use Swoft\Validator\Annotation\Mapping\StringType;

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
     *
     * @var array
     */
    protected $array;

    /**
     * @StringType()
     *
     * @var string
     */
    protected $string;

    /**
     * @IntType()
     *
     * @var int
     */
    protected $int;

    /**
     * @IsBool()
     *
     * @var int
     */
    protected $bool;


    /**
     * @IsFloat()
     *
     * @var int
     */
    protected $float;


    /**
     * @IsArray(message="array message")
     *
     * @var array
     */
    protected $arrayMessage;

    /**
     * @StringType(message="string message")
     *
     * @var string
     */
    protected $stringMessage;

    /**
     * @IntType(message="int message")
     *
     * @var int
     */
    protected $intMessage;

    /**
     * @IsBool(message="bool message")
     *
     * @var int
     */
    protected $boolMessage;

    /**
     * @IsFloat(message="float message")
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
     * @StringType()
     *
     * @var string
     */
    protected $stringDefault = '';

    /**
     * @IntType()
     *
     * @var int
     */
    protected $intDefault = 0;

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
}