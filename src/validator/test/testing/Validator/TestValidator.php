<?php declare(strict_types=1);


namespace SwoftTest\Validator\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\ArrayType;
use Swoft\Validator\Annotation\Mapping\Validator;

use Swoft\Validator\Annotation\Mapping\BoolType;
use Swoft\Validator\Annotation\Mapping\FloatType;
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
     * @ArrayType()
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
     * @BoolType()
     *
     * @var int
     */
    protected $bool;


    /**
     * @FloatType()
     *
     * @var int
     */
    protected $float;


    /**
     * @ArrayType(message="array message")
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
     * @BoolType(message="bool message")
     *
     * @var int
     */
    protected $boolMessage;

    /**
     * @FloatType(message="float message")
     *
     * @var int
     */
    protected $floatMessage;

    /**
     * @ArrayType()
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
     * @BoolType()
     *
     * @var int
     */
    protected $boolDefault = false;


    /**
     * @FloatType()
     *
     * @var int
     */
    protected $floatDefault = 1.0;
}