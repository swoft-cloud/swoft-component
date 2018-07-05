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

use Swoft\Bean\Annotation\CustomValidator as CustomValidatorAnnotation;
use Swoft\Exception\ValidatorException;
use Swoft\Helper\ValidatorHelper;
use Swoft\Validator\CustomValidator;
use Swoft\Validator\ValidatorInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class TestValidator
 * @Bean
 * @package SwoftTest\Validator
 */
class TestValidator extends CustomValidator implements ValidatorInterface
{
    public function handle($name, $value, CustomValidatorAnnotation $annotation)
    {
        if ($value !== 'limx') {
            $template = $annotation->getTemplate();
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;
            if ($annotation->getThrow()) {
                throw new ValidatorException($template);
            }
            return false;
        }
        return $value;
    }
}
