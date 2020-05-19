<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Annotation\Mapping\ValidateType;
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
    public function defaultValidator(Request $request): array
    {
        $data            = $request->getParsedBody();
        $data['kString'] = $request->parsedBody('string');
        $data['noKey']   = $request->parsedBody('noKey', 'not');

        return $data;
    }

    /**
     * @Validate(validator=UserBaseValidate::class)
     * @Validate(validator="testUserValidtor", params={1,2})
     *
     * @RequestMapping(route="userValidator")
     *
     * @param Request $request
     *
     * @return array
     */
    public function userValidator(Request $request): array
    {
        return $request->getParsedBody();
    }

    /**
     * @Validate(validator=DefaultValidator::class, type=ValidateType::GET)
     * @RequestMapping(route="defaultValidatorQuery")
     *
     * @param Request $request
     *
     * @return array
     */
    public function defaultValidatorQuery(Request $request): array
    {
        $data = $request->getParsedQuery();

        $data['kString'] = $request->parsedQuery('string');
        $data['noKey']   = $request->parsedQuery('noKey', 'not');

        return $data;
    }

    /**
     * @Validate(validator=UserBaseValidate::class, type=ValidateType::GET)
     * @Validate(validator="testUserValidtor", params={1,2}, type=ValidateType::GET)
     *
     * @RequestMapping(route="userValidatorQuery")
     *
     * @param Request $request
     *
     * @return array
     */
    public function userValidatorQuery(Request $request): array
    {
        return $request->getParsedQuery();
    }

    /**
     * @RequestMapping()
     *
     * @param Request $request
     *
     * @return array
     */
    public function noToValidate(Request $request): array
    {
        return [$request->getParsedBody()];
    }
}
