<?php declare(strict_types=1);


namespace Swoft\Validator\Contract;

/**
 * Class ValidatorInterface
 *
 * @since 2.0
 */
interface ValidatorInterface
{
    /**
     * @param array  $data
     * @param array  $params
     *
     * @return bool
     */
    public function validate(array &$data, array $params): bool;
}