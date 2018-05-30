<?php

namespace Swoft\Validator;

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\ValidatorHelper;

/**
 * String validator
 * @Bean()
 */
class DateValidator implements ValidatorInterface
{
    /**
     * @param array ...$params
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public function validate(...$params)
    {
        list($name, $value, $Date, $throws, $template) = $params;

        return ValidatorHelper::validateDate($name, $value, $Date, $throws, $template);
    }
}
