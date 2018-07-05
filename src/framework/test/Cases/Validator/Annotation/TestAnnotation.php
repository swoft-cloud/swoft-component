<?php
namespace SwoftTest\Validator\Annotation;

use Swoft\Bean\Annotation\CustomValidator;

/**
 * Class TestAnnotation
 * @Annotation
 * @Target("METHOD")
 * @package SwoftTest\Validator\Annotation
 */
class TestAnnotation extends CustomValidator
{
    /**
     * 最大长度
     *
     * @var int
     */
    protected $max = PHP_INT_MAX;

    public function __construct(array $values)
    {
        if (isset($values['max'])) {
            $this->max = $values['max'];
        }
        parent::__construct($values);
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax(int $max)
    {
        $this->max = $max;
    }
}