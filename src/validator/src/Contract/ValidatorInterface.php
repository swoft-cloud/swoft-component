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
     * @param array  $fields
     * @param array  $params
     * @param string $message
     *
     * @return bool
     */
    public function validate(array $data, array $fields, array $params, string $message): bool;
}