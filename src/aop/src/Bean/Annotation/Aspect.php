<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Bean\Annotation;

/**
 * the annotation of aspect
 *
 * @Annotation
 * @Target("CLASS")
 */
class Aspect
{
    /**
     * @var int
     */
    private $order = PHP_INT_MAX;

    /**
     * Aspect constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->order = $values['value'];
        }
        if (isset($values['order'])) {
            $this->order = $values['order'];
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }
}
