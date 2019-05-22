<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;
use SwoftTest\Http\Server\Testing\Validator\DefaultValidator;
use SwoftTest\Http\Server\Testing\Validator\UserBaseValidate;

/**
 * Class ValidatorController
 *
 * @since 2.0
 *
 * @Controller(prefix="testValidator")
 */
class ValidatorController
{
    /**
     * @Validate(validator=DefaultValidator::class)
     * @RequestMapping(route="defautValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function defaultValidator(Request $request)
    {
        $data = $request->getParsedBody();

        return $data;
    }

    /**
     * @Validate(validator=UserBaseValidate::class)
     * @Validate(validator="testUserValidtor")
     *
     * @RequestMapping(route="userValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function userValidator(Request $request)
    {
        $data = $request->getParsedBody();

        return $data;
    }
}