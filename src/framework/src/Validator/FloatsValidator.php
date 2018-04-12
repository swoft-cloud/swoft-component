<?php

namespace Swoft\Validator;

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\ValidatorHelper;

/**
 * Float validator
 * @Bean()
 */
class FloatsValidator implements ValidatorInterface
{
    /**
     * @param array ...$params
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public function validate(...$params)
    {
        list($name, $value, $min, $max) = $params;

        return ValidatorHelper::validateFloat($name, $value, $min, $max);
    }
}
