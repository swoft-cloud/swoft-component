<?php

namespace Swoft\Validator;

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\ValidatorHelper;
use Swoft\Bean\Annotation\CustomValidator as CustomValidatorAnnotation;

/**
 * CustomValidator
 */
abstract class CustomValidator implements ValidatorInterface
{
    /**
     * @param array ...$params
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public function validate(...$params)
    {
        list($name, $value, $annotation) = $params;

        return $this->handle($name, $value, $annotation);
    }

    abstract public function handle($name, $value, CustomValidatorAnnotation $annotation);
}
