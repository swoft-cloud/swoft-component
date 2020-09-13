<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Testing\Validator;

use function md5;
use function sprintf;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Contract\ValidatorInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class UserValidator
 *
 * @since 2.0
 *
 * @Validator()
 */
class UserValidator implements ValidatorInterface
{
    /**
     * @param array $data
     * @param array $params
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, array $params): array
    {
        $start          = $data['start'];
        $end            = $data['end'];
        $data['params'] = $params;

        if ($start < $end) {
            return $data;
        }

        $md = md5(JsonHelper::encode($params));
        throw new ValidatorException(sprintf('Start(%s) must less than end', $md));
    }
}
