<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Validator;

use Swoft\Exception\ValidatorException;
use Swoft\Helper\ValidatorHelper;
use Swoft\Validator\ValidatorInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class TestValidator
 * @Bean
 * @package SwoftTest\Validator
 */
class TestValidator implements ValidatorInterface
{
    public function validate(...$params)
    {
        list($name, $value, $throw, $template) = $params;
        if ($value !== 'limx') {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;
            if ($throw) {
                throw new ValidatorException($template);
            }
            return false;
        }
        return $value;
    }
}
