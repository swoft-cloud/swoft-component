<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Validator\Contract;

/**
 * Class ValidatorInterface
 *
 * @since 2.0
 */
interface ValidatorInterface
{
    /**
     * Validate error is thrown exception, otherwise is return `$data`
     *
     * @param array $data
     * @param array $params
     *
     * @return array
     */
    public function validate(array $data, array $params): array;
}
