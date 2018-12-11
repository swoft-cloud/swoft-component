<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Validator;

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\ValidatorHelper;

/**
 * Interger validator
 * @Bean
 */
class IntegerValidator
{
    /**
     * @param array ...$params
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public function validate(...$params)
    {
        list($name, $value, $min, $max, $throws, $template) = $params;

        return ValidatorHelper::validateInteger($name, $value, $min, $max, $throws, $template);
    }
}
